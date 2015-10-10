<?php

import('libs/plugins/hash.php');

//セッション情報を取得
import('app/controllers/session.php');

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
	redirect('/register');
}

//パスワードのソルトを作成
$password_salt = hash_salt();

//トランザクションを開始
db_transaction();

//ユーザを登録
$resource = insert_users(array(
	'values' => array(
		'username'      => $_SESSION['post']['user']['username'],
		'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['hash_salt']),
		'password_salt' => $password_salt,
		'name'          => $_SESSION['post']['user']['name'],
		'email'         => $_SESSION['post']['user']['email'],
		'memo'          => $_SESSION['post']['user']['memo'],
		'twostep'       => 0
	)
));
if (!$resource) {
	error('データを登録できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);

redirect('/register/complete');
