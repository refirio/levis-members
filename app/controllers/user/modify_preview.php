<?php

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//ログイン確認
if (empty($_SESSION['user'])) {
	redirect('/user');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
	redirect('/user/modify');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを登録
	redirect('/user/modify_post?token=' . token('create'));
} else {
	$view['user'] = $_SESSION['post']['user'];
}
