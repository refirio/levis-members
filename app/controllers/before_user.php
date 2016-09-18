<?php

// ログイン確認
if (!preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['auth']['user']['id']) || localdate() - $_SESSION['auth']['user']['time'] > $GLOBALS['config']['login_expire']) {
        // リダイレクト
        redirect('/user/logout');
    } else {
        $_SESSION['auth']['user']['time'] = localdate();
    }
}
