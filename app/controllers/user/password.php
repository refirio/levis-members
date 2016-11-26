<?php

import('libs/plugins/hash.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // パスワードのソルトを取得
    $users = select_users(array(
        'select' => 'password_salt',
        'where'  => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['auth']['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        $password_salt = null;
    } else {
        $password_salt = $users[0]['password_salt'];
    }

    // パスワード認証
    $users = select_users(array(
        'select' => 'id, twostep, twostep_email',
        'where'  => array(
            'id = :id AND password = :password AND regular = 1',
            array(
                'id'       => $_SESSION['auth']['user']['id'],
                'password' => hash_crypt($_POST['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']),
            ),
        ),
    ));
    if (empty($users)) {
        // パスワード認証失敗
        $_view['user'] = $_POST;

        $_view['warnings'] = array('パスワードが違います。');
    } else {
        $_SESSION['auth']['password'] = true;

        // リダイレクト
        redirect('/user/password');
    }
}

// タイトル
$_view['title'] = 'パスワード再入力サンプル';
