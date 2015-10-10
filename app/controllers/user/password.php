<?php

import('libs/plugins/hash.php');

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//ログイン確認
if (empty($_SESSION['user'])) {
	redirect('/user');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//パスワードのソルトを取得
	$users = select_users(array(
		'select' => 'password_salt',
		'where'  => array(
			'id = :id',
			array(
				'id' => $_SESSION['user']
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
			'id = :id AND password = :password',
			array(
				'id'       => $_SESSION['user'],
				'password' => hash_crypt($_POST['password'], $password_salt . ':' . $GLOBALS['hash_salt'])
			)
		)
	));
	if (empty($users)) {
		//パスワード認証失敗
		$view['user'] = $_POST;

		$view['warnings'] = array('ユーザ名もしくはパスワードが違います。');
	} else {
		$_SESSION['auth'] = true;

		redirect('/user/password');
	}
}
