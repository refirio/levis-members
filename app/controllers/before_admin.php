<?php

// ログイン確認
if (!preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['auth']['administrator']['id']) || localdate() - $_SESSION['auth']['administrator']['time'] > $GLOBALS['config']['login_expire']) {
        // リダイレクト
        redirect('/admin/logout');
    } else {
        $_SESSION['auth']['administrator']['time'] = localdate();
    }
}
