<?php

import('libs/plugins/hash.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //パスワードのソルトを取得
    $users = select_users(array(
        'select' => 'password_salt',
        'where'  => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        $password_salt = null;
    } else {
        $password_salt = $users[0]['password_salt'];
    }

    //パスワード認証
    $users = select_users(array(
        'select' => 'id, twostep, twostep_email',
        'where'  => array(
            'id = :id AND password = :password AND regular = 1',
            array(
                'id'       => $_SESSION['user']['id'],
                'password' => hash_crypt($_POST['password'], $password_salt . ':' . $GLOBALS['hash_salt']),
            ),
        ),
    ));
    if (empty($users)) {
        //パスワード認証失敗
        $view['user'] = $_POST;

        $view['warnings'] = array('パスワードが違います。');
    } else {
        $_SESSION['auth'] = true;

        //リダイレクト
        redirect('/user/password');
    }
}

//タイトル
$view['title'] = 'パスワード再入力サンプル';
