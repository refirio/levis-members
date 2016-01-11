<?php

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//トランザクションを開始
db_transaction();

//教室を削除
$resource = delete_classes(array(
	'where' => array(
		'classes.id = :id',
		array(
			'id' => $_POST['id']
		)
	)
), array(
	'associate' => 'true'
));
if (!$resource) {
	error('データを削除できません。');
}

//トランザクションを終了
db_commit();

//リダイレクト
redirect('/admin/class?ok=delete');
