<?php

//投稿データを確認
if (empty($_SESSION['post'])) {
	redirect('/register');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを登録
	redirect('/register/post?token=' . token('create'));
} else {
	$view['user'] = $_SESSION['post']['user'];

	//ワンタイムトークン
	$view['token'] = token('create');
}
