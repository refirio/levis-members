<?php

import('libs/plugins/validator.php');

/*
 * ユーザの取得
 */
function select_users($queries, $options = array())
{
	$queries = db_placeholder($queries);

	//ユーザを取得
	$queries['from'] = DATABASE_PREFIX . 'users';

	//削除済みデータは取得しない
	if (!isset($queries['where'])) {
		$queries['where'] = 'TRUE';
	}
	$queries['where'] = 'deleted IS NULL AND (' . $queries['where'] . ')';

	//データを取得
	$results = db_select($queries);

	return $results;
}

/*
 * ユーザの登録
 */
function insert_users($queries, $options = array())
{
	$queries = db_placeholder($queries);

	//初期値を取得
	$defaults = default_users();

	if (isset($queries['values']['created'])) {
		if ($queries['values']['created'] === false) {
			unset($queries['values']['created']);
		}
	} else {
		$queries['values']['created'] = $defaults['created'];
	}
	if (isset($queries['values']['modified'])) {
		if ($queries['values']['modified'] === false) {
			unset($queries['values']['modified']);
		}
	} else {
		$queries['values']['modified'] = $defaults['modified'];
	}

	//データを登録
	$queries['insert_into'] = DATABASE_PREFIX . 'users';

	$resource = db_insert($queries);

	return $resource;
}

/*
 * ユーザの編集
 */
function update_users($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'id'     => isset($options['id'])     ? $options['id']     : null,
		'update' => isset($options['update']) ? $options['update'] : null
	);

	//最終編集日時を確認
	if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
		$users = db_select(array(
			'from'  => DATABASE_PREFIX . 'users',
			'where' => array(
				'id = :id AND modified > :update',
				array(
					'id'     => $options['id'],
					'update' => $options['update']
				)
			)
		));
		if (!empty($users)) {
			error('編集開始後にデータが更新されています。');
		}
	}

	//初期値を取得
	$defaults = default_users();

	if (isset($queries['set']['modified'])) {
		if ($queries['set']['modified'] === false) {
			unset($queries['set']['modified']);
		}
	} else {
		$queries['set']['modified'] = $defaults['modified'];
	}

	//データを編集
	$queries['update'] = DATABASE_PREFIX . 'users';

	$resource = db_update($queries);

	return $resource;
}

/*
 * ユーザの削除
 */
function delete_users($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true
	);

	if ($options['softdelete'] == true) {
		//データを編集
		$resource = db_update(array(
			'update' => DATABASE_PREFIX . 'users',
			'set'    => array(
				'deleted'  => localdate('Y-m-d H:i:s'),
				'username' => array('CONCAT(\'DELETED ' . localdate('YmdHis') . ' \', username)'),
				'email'    => array('CONCAT(\'DELETED ' . localdate('YmdHis') . ' \', email)')
			),
			'where'  => isset($queries['where']) ? $queries['where'] : '',
			'limit'  => isset($queries['limit']) ? $queries['limit'] : ''
		));
	} else {
		//データを削除
		$resource = db_delete(array(
			'delete_from' => DATABASE_PREFIX . 'users',
			'where'       => isset($queries['where']) ? $queries['where'] : '',
			'limit'       => isset($queries['limit']) ? $queries['limit'] : ''
		));
	}

	return $resource;
}

/*
 * ユーザの正規化
 */
function normalize_users($queries, $options = array())
{
	//2段階認証用メールアドレス
	if (isset($queries['twostep_email'])) {
		if (is_array($queries['twostep_email'])) {
			$queries['twostep_email'] = $queries['twostep_email']['account'] . '@' . $queries['twostep_email']['domain'];
		}
	}

	return $queries;
}

/*
 * ユーザの検証
 */
function validate_users($queries, $options = array())
{
	$options = array(
		'duplicate' => isset($options['duplicate']) ? $options['duplicate'] : true
	);

	$messages = array();

	//ユーザ名
	if (isset($queries['username'])) {
		if (!validator_required($queries['username'])) {
			$messages['username'] = 'ユーザ名が入力されていません。';
		} elseif (!validator_alpha_dash($queries['username'])) {
			$messages['username'] = 'ユーザ名は半角英数字で入力してください。';
		} elseif (!validator_between($queries['username'], 4, 20)) {
			$messages['username'] = 'ユーザ名は4文字以上20文字以内で入力してください。';
		} elseif (validator_regexp($queries['username'], '(account|admin|alias|api|app|auth|config|contact|debug|default|develop|error|example|guest|help|home|index|info|inquiry|login|logout|master|register|root|sample|setting|signin|signout|signup|staff|status|support|system|test|user|version|www)')) {
			$messages['username'] = '入力されたユーザ名は使用できません。';
		} elseif ($options['duplicate'] == true) {
			if ($queries['id']) {
				$users = db_select(array(
					'select' => 'id',
					'from'   => DATABASE_PREFIX . 'users',
					'where'  => array(
						'id != :id AND username = :username',
						array(
							'id'       => $queries['id'],
							'username' => $queries['username']
						)
					)
				));
			} else {
				$users = db_select(array(
					'select' => 'id',
					'from'   => DATABASE_PREFIX . 'users',
					'where'  => array(
						'username = :username',
						array(
							'username' => $queries['username']
						)
					)
				));
			}
			if (!empty($users)) {
				$messages['username'] = '入力されたユーザ名はすでに使用されています。';
			}
		}
	}

	//パスワード
	if (isset($queries['password'])) {
		$flag = false;
		if ($queries['id']) {
			if ($queries['password'] != '') {
				$flag = true;
			}
		} else {
			if (!validator_required($queries['password'])) {
				$messages['password'] = 'パスワードが入力されていません。';
			} else {
				$flag = true;
			}
		}
		if ($flag == true) {
			if (!validator_regexp($queries['password'], '^[\w\.\~\-\/\?\&\#\+\=\:\;\@\%\!]+$')) {
				$messages['password'] = 'パスワードは半角英数字記号で入力してください。';
			} elseif (validator_regexp($queries['password'], '^([a-zA-Z]+|[0-9]+)$')) {
				$messages['password'] = 'パスワードは英数字を混在させてください。';
			} elseif (!validator_between($queries['password'], 8, 40)) {
				$messages['password'] = 'パスワードは8文字以上40文字以内で入力してください。';
			} elseif (isset($queries['username']) && validator_equals($queries['password'], $queries['username'])) {
				$messages['password'] = 'パスワードはユーザ名とは異なるものを入力してください。';
			} elseif (!validator_equals($queries['password'], $queries['password_confirm'])) {
				$messages['password'] = 'パスワードと確認パスワードが一致しません。';
			}
		}
	}

	//名前
	if (isset($queries['name'])) {
		if (!validator_required($queries['name'])) {
			$messages['name'] = '名前が入力されていません。';
		} elseif (!validator_max_length($queries['name'], 20)) {
			$messages['name'] = '名前は20文字以内で入力してください。';
		}
	}

	//メールアドレス
	if (isset($queries['email'])) {
		if (!validator_required($queries['email'])) {
			$messages['email'] = 'メールアドレスが入力されていません。';
		} elseif (!validator_email($queries['email'])) {
			$messages['email'] = 'メールアドレスの入力内容が正しくありません。';
		} elseif (!validator_max_length($queries['email'], 80)) {
			$messages['email'] = 'メールアドレスは80文字以内で入力してください。';
		} else {
			if ($queries['id']) {
				$users = db_select(array(
					'select' => 'id',
					'from'   => DATABASE_PREFIX . 'users',
					'where'  => array(
						'id != :id AND email = :email',
						array(
							'id'    => $queries['id'],
							'email' => $queries['email']
						)
					)
				));
			} else {
				$users = db_select(array(
					'select' => 'id',
					'from'   => DATABASE_PREFIX . 'users',
					'where'  => array(
						'email = :email',
						array(
							'email' => $queries['email']
						)
					)
				));
			}
			if (!empty($users)) {
				$messages['email'] = '入力されたメールアドレスはすでに使用されています。';
			}
		}
	}

	//メモ
	if (isset($queries['memo'])) {
		if (!validator_required($queries['memo'])) {
		} elseif (!validator_max_length($queries['memo'], 1000)) {
			$messages['memo'] = 'メモは1000文字以内で入力してください。';
		}
	}

	//暗証コード
	if (isset($queries['token_code'])) {
		if (!validator_required($queries['token_code'])) {
			$messages['token_code'] = '暗証コードが入力されていません。';
		} else {
			$users = db_select(array(
				'select' => 'id',
				'from'   => DATABASE_PREFIX . 'users',
				'where'  => array(
					'email = :email AND token_code = :token_code',
					array(
						'email'      => $queries['key'],
						'token_code' => $queries['token_code']
					)
				)
			));
			if (empty($users)) {
				$messages['token_code'] = '暗証コードが違います。';
			}
		}
	}

	//2段階認証
	if (isset($queries['twostep'])) {
		if (!validator_boolean($queries['twostep'])) {
			$messages['twostep'] = '2段階認証の書式が不正です。';
		}
	}

	//2段階認証用メールアドレス
	if (isset($queries['twostep_email'])) {
		if ($queries['twostep'] == 1) {
			if (!validator_required($queries['twostep_email'])) {
				$messages['twostep_email'] = '2段階認証用メールアドレスが入力されていません。';
			} elseif (!validator_email($queries['twostep_email'])) {
				$messages['twostep_email'] = '2段階認証用メールアドレスの入力内容が正しくありません。';
			} elseif (!validator_regexp($queries['twostep_email'], '@(' . implode('|', array_map('preg_quote', $GLOBALS['carriers'], array('/'))) . ')$')) {
				$messages['twostep_email'] = '指定されたドメインは使用できません。';
			}
		}
	}

	return $messages;
}

/*
 * ユーザのフォーム用データ作成
 */
function form_users($data)
{
	//2段階認証用メールアドレス
	if (isset($data['twostep_email'])) {
		if (preg_match('/^(.+)@(.+)$/', $data['twostep_email'], $matches)) {
			$data['twostep_email'] = array(
				'account' => $matches[1],
				'domain'  => $matches[2]
			);
		} else {
			$data['twostep_email'] = array(
				'account' => '',
				'domain'  => ''
			);
		}
	}

	return $data;
}

/*
 * ユーザの初期値
 */
function default_users()
{
	return array(
		'id'             => null,
		'created'        => localdate('Y-m-d H:i:s'),
		'modified'       => localdate('Y-m-d H:i:s'),
		'deleted'        => null,
		'username'       => '',
		'password'       => '',
		'password_salt'  => '',
		'name'           => '',
		'email'          => '',
		'memo'           => null,
		'loggedin'       => null,
		'failed'         => null,
		'failed_last'    => null,
		'token'          => null,
		'token_code'     => null,
		'token_expire'   => null,
		'twostep'        => 0,
		'twostep_email'  => null,
		'twostep_code'   => null,
		'twostep_expire' => null
	);
}
