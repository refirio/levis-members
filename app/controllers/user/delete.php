<?php

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//トランザクションを開始
db_transaction();

//ユーザを削除
$resource = delete_users(array(
	'where' => array(
		'id = :id',
		array(
			'id' => $_SESSION['user']
		)
	)
));
if (!$resource) {
	error('データを削除できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['user']);

redirect('/user/delete_complete');
