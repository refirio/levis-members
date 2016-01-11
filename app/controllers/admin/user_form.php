<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを整理
	$post = array(
		'user' => normalize_classes(array(
			'id'               => isset($_POST['id'])               ? $_POST['id']               : '',
			'username'         => isset($_POST['username'])         ? $_POST['username']         : '',
			'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
			'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
			'name'             => isset($_POST['name'])             ? $_POST['name']             : '',
			'email'            => isset($_POST['email'])            ? $_POST['email']            : '',
			'memo'             => isset($_POST['memo'])             ? $_POST['memo']             : ''
		))
	);

	if (isset($_POST['preview']) && $_POST['preview'] == 'yes') {
		//プレビュー
		$view['user'] = $post['user'];
	} else {
		//入力データを検証＆登録
		$warnings = validate_users($post['user']);
		if (isset($_POST['type']) && $_POST['type'] == 'json') {
			if (empty($warnings)) {
				ok();
			} else {
				warning($warnings);
			}
		} else {
			if (empty($warnings)) {
				$_SESSION['post']['user'] = $post['user'];

				redirect('/admin/user_post?token=' . token('create'));
			} else {
				$view['user'] = $post['user'];

				$view['warnings'] = $warnings;
			}
		}
	}
} else {
	//初期データを取得
	if (empty($_GET['id'])) {
		$view['user'] = default_users();

		//タイトル
		$view['title'] = 'ユーザ登録';
	} else {
		$users = select_users(array(
			'where' => array(
				'id = :id',
				array(
					'id' => $_GET['id']
				)
			)
		));
		if (empty($users)) {
			warning('編集データが見つかりません。');
		} else {
			$view['user'] = $users[0];
		}

		//タイトル
		$view['title'] = 'ユーザ編集';
	}

	//投稿セッションを初期化
	unset($_SESSION['post']);

	//編集開始日時を記録
	if (!empty($_GET['id'])) {
		$_SESSION['update'] = localdate('Y-m-d H:i:s');
	}
}
