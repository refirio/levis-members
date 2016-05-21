<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'user' => normalize_users(array(
            'id'               => null,
            'username'         => isset($_POST['username'])         ? $_POST['username']         : '',
            'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
            'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
            'key'              => isset($_POST['key'])              ? $_POST['key']              : '',
            'token_code'       => isset($_POST['token_code'])       ? $_POST['token_code']       : '',
        )),
    );

    //入力データを検証＆登録
    $warnings = validate_users($post['user']);
    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['user'] = $post['user'];

            //リダイレクト
            redirect('/register/preview');
        } else {
            $view['user'] = $post['user'];

            $view['key']  = $post['user']['key'];

            $view['warnings'] = $warnings;
        }
    }
} elseif (isset($_GET['referer']) && $_GET['referer'] === 'preview') {
    //入力データを復元
    $view['user'] = $_SESSION['post']['user'];

    $view['key'] = $_SESSION['post']['user']['key'];
} else {
    //ユーザ登録用URLを検証
    $users = select_users(array(
        'select' => 'token_expire',
        'where'  => array(
            'email = :email AND regular = 0 AND token = :token',
            array(
                'email' => $_GET['key'],
                'token' => $_GET['token'],
            ),
        ),
    ));
    if (empty($users)) {
        error('不正なアクセスです。');
    }

    if (localdate(null, $users[0]['token_expire']) < localdate()) {
        error('URLの有効期限が終了しています。');
    }

    //初期データを取得
    $view['user'] = default_users();

    $view['key'] = $_GET['key'];

    //投稿セッションを初期化
    unset($_SESSION['post']);
}

//タイトル
$view['title'] = 'ユーザ登録';
