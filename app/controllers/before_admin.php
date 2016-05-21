<?php

//ログイン確認
if (!preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['administrator']['id']) || localdate() - $_SESSION['administrator']['time'] > $GLOBALS['login_expire']) {
        //リダイレクト
        redirect('/admin/logout');
    } else {
        $_SESSION['administrator']['time'] = localdate();
    }
}
