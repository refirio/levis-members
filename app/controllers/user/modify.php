<?php

import('libs/plugins/array.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正な操作が検出されました。送信内容を確認して再度実行してください。');
    }

    // アクセス元
    if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
        error('不正なアクセスです。');
    }

    // 入力データを整理
    $post = array(
        'user'    => normalize_users(array(
            'id'               => $_SESSION['auth']['user']['id'],
            'username'         => isset($_POST['username'])         ? $_POST['username']         : '',
            'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
            'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
            'email'            => isset($_POST['email'])            ? $_POST['email']            : '',
        )),
        'profile' => normalize_profiles(array(
            'user_id' => $_SESSION['auth']['user']['id'],
            'name'    => isset($_POST['profile_name']) ? $_POST['profile_name'] : '',
            'text'    => isset($_POST['profile_text']) ? $_POST['profile_text'] : '',
        )),
    );

    // 入力データを検証＆登録
    $warnings  = validate_users($post['user']);
    $warnings += array_key_prefix(validate_profiles($post['profile']), 'profile_');
    if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['user']    = $post['user'];
            $_SESSION['post']['profile'] = $post['profile'];

            // リダイレクト
            redirect('/user/modify_preview');
        } else {
            $_view['user']    = $post['user'];
            $_view['profile'] = $post['profile'];

            $_view['warnings'] = $warnings;
        }
    }
} elseif (isset($_GET['referer']) && $_GET['referer'] === 'preview') {
    // 入力データを復元
    $_view['user']    = $_SESSION['post']['user'];
    $_view['profile'] = $_SESSION['post']['profile'];
} else {
    // 初期データを取得
    $users = service_user_select(array(
        'where' => array(
            'id = :id',
            array(
                'id' => $_SESSION['auth']['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        warning('編集データが見つかりません。');
    } else {
        $_view['user'] = $users[0];

        $_view['user']['password'] = '';
    }

    $profiles = service_profile_select(array(
        'where' => array(
            'user_id = :id',
            array(
                'id' => $_SESSION['auth']['user']['id'],
            ),
        ),
    ));
    if (empty($profiles)) {
        warning('編集データが見つかりません。');
    } else {
        $_view['profile'] = $profiles[0];
    }

    // 投稿セッションを初期化
    unset($_SESSION['post']);

    // 編集開始日時を記録
    $_SESSION['update']['user'] = localdate('Y-m-d H:i:s');
}

// タイトル
$_view['title'] = 'ユーザ情報編集';
