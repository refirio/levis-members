<?php

//ログイン確認
if (!preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['user']['id']) || localdate() - $_SESSION['user']['time'] > $GLOBALS['config']['login_expire']) {
        //リダイレクト
        redirect('/user/logout');
    } else {
        $_SESSION['user']['time'] = localdate();
    }
}
