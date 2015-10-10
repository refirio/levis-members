<?php

//ログイン確認
if (empty($_SESSION['administrator'])) {
	redirect('/admin');
}

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
	redirect('/admin/member_form');
}

//トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['member']['id'])) {
	//名簿を登録
	$resource = insert_members(array(
		'values' => array(
			'class_id'  => $_SESSION['post']['member']['class_id'],
			'name'      => $_SESSION['post']['member']['name'],
			'name_kana' => $_SESSION['post']['member']['name_kana'],
			'grade'     => $_SESSION['post']['member']['grade'],
			'birthday'  => $_SESSION['post']['member']['birthday'],
			'email'     => $_SESSION['post']['member']['email'],
			'tel'       => $_SESSION['post']['member']['tel'],
			'memo'      => $_SESSION['post']['member']['memo'],
			'public'    => $_SESSION['post']['member']['public']
		)
	), array(
		'files' => array(
			'image_01' => isset($_SESSION['file']['member']['image_01']) ? $_SESSION['file']['member']['image_01'] : array(),
			'image_02' => isset($_SESSION['file']['member']['image_02']) ? $_SESSION['file']['member']['image_02'] : array()
		)
	));
	if (!$resource) {
		error('データを登録できません。');
	}
} else {
	//名簿を編集
	$resource = update_members(array(
		'set'  => array(
			'class_id'  => $_SESSION['post']['member']['class_id'],
			'name'      => $_SESSION['post']['member']['name'],
			'name_kana' => $_SESSION['post']['member']['name_kana'],
			'grade'     => $_SESSION['post']['member']['grade'],
			'birthday'  => $_SESSION['post']['member']['birthday'],
			'email'     => $_SESSION['post']['member']['email'],
			'tel'       => $_SESSION['post']['member']['tel'],
			'memo'      => $_SESSION['post']['member']['memo'],
			'public'    => $_SESSION['post']['member']['public']
		),
		'where' => array(
			'id = :id',
			array(
				'id' => $_SESSION['post']['member']['id']
			)
		)
	), array(
		'id'     => intval($_SESSION['post']['member']['id']),
		'update' => $_SESSION['update'],
		'files'  => array(
			'image_01' => isset($_SESSION['file']['member']['image_01']) ? $_SESSION['file']['member']['image_01'] : array(),
			'image_02' => isset($_SESSION['file']['member']['image_02']) ? $_SESSION['file']['member']['image_02'] : array()
		)
	));
	if (!$resource) {
		error('データを編集できません。');
	}
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['file']);
unset($_SESSION['update']);

redirect('/admin/member?ok=post');
