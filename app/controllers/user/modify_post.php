<?php

import('libs/plugins/hash.php');

//セッション情報を取得
import('app/controllers/session.php');

//ログイン確認
if (empty($_SESSION['user'])) {
	redirect('/user');
}

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
	redirect('/user/modify');
}

//パスワードのソルトを作成
$password_salt = hash_salt();

//トランザクションを開始
db_transaction();

//ユーザを編集
$sets = array(
	'username' => $_SESSION['post']['user']['username'],
	'name'     => $_SESSION['post']['user']['name'],
	'email'    => $_SESSION['post']['user']['email'],
	'memo'     => $_SESSION['post']['user']['memo']
);
if (!empty($_SESSION['post']['user']['password'])) {
	$sets['password']      = hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['hash_salt']);
	$sets['password_salt'] = $password_salt;
}
$resource = update_users(array(
	'set'   => $sets,
	'where' => array(
		'id = :id',
		array(
			'id' => $_SESSION['user']
		)
	)
), array(
	'id'     => intval($_SESSION['user']),
	'update' => $_SESSION['update']
));
if (!$resource) {
	error('データを編集できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

redirect('/user/modify_complete');
