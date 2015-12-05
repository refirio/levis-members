<?php

//設定ファイル
import('app/config.php');

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//ログイン確認
if ($_REQUEST['mode'] == 'admin' && !regexp_match('^(index|logout)$', $_REQUEST['work'])) {
	if (empty($_SESSION['administrator'])) {
		redirect('/admin');
	}
} elseif ($_REQUEST['mode'] == 'user' && !regexp_match('^(index|logout)$', $_REQUEST['work'])) {
	if (empty($_SESSION['user'])) {
		redirect('/user');
	}
}
