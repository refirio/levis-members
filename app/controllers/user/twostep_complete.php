<?php

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//ログイン確認
if (empty($_SESSION['user'])) {
	redirect('/user');
}
