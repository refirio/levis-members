<?php

// ログイン確認
if (!preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['auth']['user']['id']) || localdate() - $_SESSION['auth']['user']['time'] > $GLOBALS['config']['login_expire']) {
        $referer = '/' . implode('/', $params);

        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '') {
            $referer .= '?' . $_SERVER['QUERY_STRING'];
        }

        // リダイレクト
        redirect('/user/logout?referer=' . urlencode($referer));
    } else {
        $_SESSION['auth']['user']['time'] = localdate();
    }
}
