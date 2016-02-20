<?php

//設定ファイル
import('app/config.php');

//オートログイン
if (empty($_SESSION['session']) && !empty($_COOKIE['session'])) {
    list($session, $user_id) = service_user_autologin($_COOKIE['session']);
    if ($session == true) {
        $_SESSION['session'] = $session;
        $_SESSION['user']    = $user_id;
    }
}

//ユーザ確認
if (!empty($_SESSION['user'])) {
    $users = select_users(array(
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['user'],
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
if ($_REQUEST['mode'] == 'admin' && !regexp_match('^(index|logout)$', $_REQUEST['work'])) {
    if (empty($_SESSION['administrator'])) {
        //リダイレクト
        redirect('/admin');
    }
} elseif ($_REQUEST['mode'] == 'user' && !regexp_match('^(index|logout)$', $_REQUEST['work'])) {
    if (empty($_SESSION['user'])) {
        //リダイレクト
        redirect('/user');
    }
}
