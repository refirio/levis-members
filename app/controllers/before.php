<?php

//設定ファイル
import('app/config.php');

if (is_file(MAIN_PATH . MAIN_APPLICATION_PATH . 'app/config.local.php')) {
    import('app/config.local.php');
}

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
