<?php

import('libs/plugins/array.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
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

    //入力データを検証＆登録
    $warnings  = validate_users($post['user']);
    $warnings += array_key_prefix(validate_profiles($post['profile']), 'profile_');
    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['user']    = $post['user'];
            $_SESSION['post']['profile'] = $post['profile'];

            //リダイレクト
            redirect('/user/modify_preview');
        } else {
            $view['user']    = $post['user'];
            $view['profile'] = $post['profile'];

            $view['warnings'] = $warnings;
        }
    }
} elseif (isset($_GET['referer']) && $_GET['referer'] === 'preview') {
    //入力データを復元
    $view['user']    = $_SESSION['post']['user'];
    $view['profile'] = $_SESSION['post']['profile'];
} else {
    //初期データを取得
    $users = select_users(array(
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['auth']['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        warning('編集データが見つかりません。');
    } else {
        $view['user'] = $users[0];

        $view['user']['password'] = '';
    }

    $profiles = select_profiles(array(
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
        $view['profile'] = $profiles[0];
    }

    //投稿セッションを初期化
    unset($_SESSION['post']);

    //編集開始日時を記録
    $_SESSION['update']['user'] = localdate('Y-m-d H:i:s');
}

//タイトル
$view['title'] = 'ユーザ情報編集';
