<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //ログイン
    foreach ($GLOBALS['administrators'] as $username => $information) {
        if ($_POST['username'] == $username && $_POST['password'] == $information['password'] && preg_match('/' . $information['address'] . '/', clientip())) {
            $_SESSION['administrator'] = true;

            break;
        }
    }

    if (empty($_SESSION['administrator'])) {
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
if (!empty($_SESSION['administrator'])) {
    //リダイレクト
    redirect('/admin/home');
}

//タイトル
$view['title'] = '管理者用';
