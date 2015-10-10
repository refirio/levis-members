<?php

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//暗証コードを確認
if (empty($_SESSION['token_code'])) {
	redirect('/password');
}
