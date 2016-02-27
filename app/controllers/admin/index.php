<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //ログイン
    foreach ($GLOBALS['administrators'] as $username => $information) {
        if ($_POST['username'] == $username && $_POST['password'] == $information['password']) {
            if (empty($information['address']) || in_array(clientip(), $information['address'])) {
                $_SESSION['administrator'] = array(
                    'id'   => $_POST['username'],
                    'time' => localdate(),
                );

                break;
            }
        }
    }

    if (empty($_SESSION['administrator']['id'])) {
        $view['administrator'] = $_POST;

        $view['warnings'] = array('ユーザ名もしくはパスワードが違います。');
    }
} else {
    $view['administrator'] = array(
        'username' => '',
        'password' => '',
    );
}

//ログイン確認
if (!empty($_SESSION['administrator']['id'])) {
    //リダイレクト
    redirect('/admin/home');
}

//タイトル
$view['title'] = '管理者用';
