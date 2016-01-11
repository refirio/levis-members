<?php

import('libs/plugins/cookie.php');
import('libs/plugins/hash.php');
import('libs/plugins/mail.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ログイン失敗回数を判定
	$users = select_users(array(
		'select' => 'failed, failed_last',
		'where'  => array(
			'username = :username AND failed IS NOT NULL AND failed_last IS NOT NULL',
			array(
				'username' => $_POST['username']
			)
		)
	));
	if (empty($users)) {
		$failed      = null;
		$failed_last = null;
	} else {
		$failed      = $users[0]['failed'];
		$failed_last = $users[0]['failed_last'];

		if (localdate(null, $failed_last) + 60 * 30 > localdate() && $failed >= 10) {
			error('パスワードを連続して間違えたため、このアカウントは一定期間凍結されています。');
		}
	}

	//パスワードのソルトを取得
	$users = select_users(array(
		'select' => 'password_salt',
		'where'  => array(
			'username = :username',
			array(
				'username' => $_POST['username']
			)
		)
	));
	if (empty($users)) {
		$password_salt = null;
	} else {
		$password_salt = $users[0]['password_salt'];
	}

	//パスワード認証
	$users = select_users(array(
		'select' => 'id, twostep, twostep_email',
		'where'  => array(
			'username = :username AND password = :password',
			array(
				'username' => $_POST['username'],
				'password' => hash_crypt($_POST['password'], $password_salt . ':' . $GLOBALS['hash_salt'])
			)
		)
	));
	if (empty($users)) {
		//パスワード認証失敗
		$view['user'] = $_POST;

		$view['warnings'] = array('ユーザ名もしくはパスワードが違います。');

		//トランザクションを開始
		db_transaction();

		//認証失敗回数を記録
		$resource = update_users(array(
			'set'   => array(
				'failed'      => $failed + 1,
				'failed_last' => localdate('Y-m-d H:i:s')
			),
			'where' => array(
				'username = :username',
				array(
					'username' => $_POST['username']
				)
			)
		));
		if (!$resource) {
			error('データを編集できません。');
		}

		//トランザクションを終了
		db_commit();
	} else {
		//パスワード認証成功
		$id            = $users[0]['id'];
		$twostep       = $users[0]['twostep'];
		$twostep_email = $users[0]['twostep_email'];
		$success       = true;

		//2段階認証の状態を取得
		$session_twostep = 0;
		if ($twostep == 1 && isset($_COOKIE['session'])) {
			$sessions = select_sessions(array(
				'select' => 'twostep',
				'where'  => array(
					'id = :session AND user_id = :user_id',
					array(
						'session' => $_COOKIE['session'],
						'user_id' => $id
					)
				)
			));
			if (!empty($sessions)) {
				$session_twostep = $sessions[0]['twostep'];
			}
		}

		//2段階認証
		if ($twostep == 1 && $session_twostep == 0) {
			$view['user'] = $_POST;

			$view['twostep'] = true;

			$success = false;

			if (isset($_POST['twostep_code'])) {
				//2段階認証用コードを確認
				$users = select_users(array(
					'select' => 'id, twostep_expire',
					'where'  => array(
						'username = :username AND password = :password AND twostep_code = :twostep_code',
						array(
							'username'     => $_POST['username'],
							'password'     => hash_crypt($_POST['password'], $password_salt . ':' . $GLOBALS['hash_salt']),
							'twostep_code' => $_POST['twostep_code']
						)
					)
				));
				if (empty($users)) {
					$view['warnings'] = array('2段階認証用コードが違います。');
				} else {
					if (localdate(null, $users[0]['twostep_expire']) < localdate()) {
						$view['warnings'] = array('2段階認証用コードの有効期限が終了しています。');
					} else {
						$success = true;
					}
				}
			} else {
				//2段階認証用コードを作成
				$twostep_code = rand_string(6);

				//トランザクションを開始
				db_transaction();

				//2段階認証用コードを通知
				$resource = update_users(array(
					'set'   => array(
						'twostep_code'   => $twostep_code,
						'twostep_expire' => localdate('Y-m-d H:i:s', time() + 60 * 60 * 24)
					),
					'where' => array(
						'id = :id',
						array(
							'id' => $id
						)
					)
				));
				if (!$resource) {
					error('指定されたユーザが見つかりません。');
				}

				//メール送信内容を作成
				$view['code'] = $twostep_code;

				$to      = $twostep_email;
				$subject = $GLOBALS['mail_subjects']['user/twostep'];
				$message = view('mail/user/twostep.php', true);
				$headers = $GLOBALS['mail_headers'];

				//メールを送信
				if (mail_send($to, $subject, $message, $headers) == false) {
					error('メールを送信できません。');
				}

				//トランザクションを終了
				db_commit();
			}
		}

		if ($success) {
			//認証成功
			$_SESSION['user'] = $id;

			//トランザクションを開始
			db_transaction();

			//認証失敗回数をリセット
			$resource = update_users(array(
				'set'   => array(
					'loggedin'    => localdate('Y-m-d H:i:s'),
					'failed'      => null,
					'failed_last' => null
				),
				'where' => array(
					'username = :username',
					array(
						'username' => $_POST['username']
					)
				)
			));
			if (!$resource) {
				error('データを編集できません。');
			}

			//ログイン状態を保持
			$session = rand_string();

			if (isset($_POST['session']) && $_POST['session'] == 'keep') {
				$keep = 1;
			} else {
				$keep = 0;
			}
			if ($session_twostep == 1 || (isset($_POST['twostep_session']) && $_POST['twostep_session'] == 'keep')) {
				$twostep = 1;
			} else {
				$twostep = 0;
			}

			//セッション情報を取得
			$flag = false;
			if (isset($_COOKIE['session'])) {
				$users = select_sessions(array(
					'select' => 'user_id',
					'where'  => array(
						'id = :id',
						array(
							'id' => $_COOKIE['session']
						)
					)
				));
				if (!empty($users)) {
					$flag = true;
				}
			}

			//セッション情報を更新
			if ($flag == true) {
				$resource = update_sessions(array(
					'set'   => array(
						'id'      => $session,
						'user_id' => $_SESSION['user'],
						'agent'   => $_SERVER['HTTP_USER_AGENT'],
						'keep'    => $keep,
						'twostep' => $twostep,
						'expire'  => localdate('Y-m-d H:i:s', time() + $GLOBALS['cookie_expire'])
					),
					'where' => array(
						'id = :id',
						array(
							'id' => $_COOKIE['session']
						)
					)
				));
				if (!$resource) {
					error('データを編集できません。');
				}
			} else {
				$resource = insert_sessions(array(
					'values' => array(
						'id'      => $session,
						'user_id' => $_SESSION['user'],
						'agent'   => $_SERVER['HTTP_USER_AGENT'],
						'keep'    => $keep,
						'twostep' => $twostep,
						'expire'  => localdate('Y-m-d H:i:s', time() + $GLOBALS['cookie_expire'])
					)
				));
				if (!$resource) {
					error('データを登録できません。');
				}
			}

			cookie_set('session', $session, time() + $GLOBALS['cookie_expire']);

			//古いセッションを削除
			$resource = delete_sessions(array(
				'where' => array(
					'expire < :expire',
					array(
						'expire' => localdate('Y-m-d H:i:s')
					)
				)
			));
			if (!$resource) {
				error('データを削除できません。');
			}

			//トランザクションを終了
			db_commit();
		}
	}
} else {
	$view['user'] = array(
		'username' => '',
		'password' => '',
		'session'  => null
	);
}

//ログイン確認
if (!empty($_SESSION['user'])) {
	redirect('/user/home');
}

//タイトル
$view['title'] = 'ログイン';
