<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを整理
	$post = array(
		'user' => normalize_users(array(
			'id'               => null,
			'key'              => isset($_POST['key'])              ? $_POST['key']              : '',
			'token_code'       => isset($_POST['token_code'])       ? $_POST['token_code']       : '',
			'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
			'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : ''
		))
	);

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

			redirect('/password/post?token=' . token('create'));
		} else {
			$view['user'] = $post['user'];

			$view['key']  = $post['key'];

			$view['warnings'] = $warnings;
		}
	}
} else {
	//パスワード再発行用URLを検証
	$users = select_users(array(
		'select' => 'token_expire',
		'where'  => array(
			'email = :email AND token = :token',
			array(
				'email' => $_GET['key'],
				'token' => $_GET['token']
			)
		)
	));
	if (empty($users)) {
		error('不正なアクセスです。');
	}

	if (localdate(null, $users[0]['token_expire']) < localdate()) {
		error('URLの有効期限が終了しています。');
	}

	$view['user'] = array(
		'password' => ''
	);

	$view['key'] = $_GET['key'];

	unset($_SESSION['post']);
}
