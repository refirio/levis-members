<?php

//設定ファイル
import('app/config.php');

//オートログイン
if (empty($_SESSION['session']) && !empty($_COOKIE['session'])) {
    list($session, $user_id) = service_user_autologin($_COOKIE['session']);
    if ($session === true) {
        $_SESSION['session'] = $session;
        $_SESSION['user']    = array(
            'id'   => $user_id,
            'time' => localdate(),
        );
    }
}

//ユーザ存在確認
if (!empty($_SESSION['user']['id'])) {
    $users = select_users(array(
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        unset($_SESSION['user']);

        //リダイレクト
        redirect('/user');
    } else {
        $view['_user'] = $users[0];
    }
}

//ログイン確認
if ($_REQUEST['mode'] === 'admin' && !preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['administrator']['id']) || localdate() - $_SESSION['administrator']['time'] > $GLOBALS['login_expire']) {
        //リダイレクト
        redirect('/admin/logout');
    } else {
        $_SESSION['administrator']['time'] = localdate();
    }
} elseif ($_REQUEST['mode'] === 'user' && !preg_match('/^(index|logout)$/', $_REQUEST['work'])) {
    if (empty($_SESSION['user']['id']) || localdate() - $_SESSION['user']['time'] > $GLOBALS['login_expire']) {
        //リダイレクト
        redirect('/user/logout');
    } else {
        $_SESSION['user']['time'] = localdate();
    }
}
