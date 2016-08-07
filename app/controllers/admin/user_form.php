<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if ((empty($_POST['view']) || $_POST['view'] !== 'preview') && !token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'user' => normalize_users(array(
            'id'               => isset($_POST['id'])               ? $_POST['id']               : '',
            'username'         => isset($_POST['username'])         ? $_POST['username']         : '',
            'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
            'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
            'email'            => isset($_POST['email'])            ? $_POST['email']            : '',
        )),
    );

    if (isset($_POST['view']) && $_POST['view'] === 'preview') {
        //プレビュー
        $view['user'] = $post['user'];
    } else {
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

                //フォワード
                forward('/admin/user_post');
            } else {
                $view['user'] = $post['user'];

                $view['warnings'] = $warnings;
            }
        }
    }
} else {
    //初期データを取得
    if (empty($_GET['id'])) {
        $view['user'] = default_users();
    } else {
        $users = select_users(array(
            'where' => array(
                'id = :id AND regular = 1',
                array(
                    'id' => $_GET['id'],
                ),
            ),
        ));
        if (empty($users)) {
            warning('編集データが見つかりません。');
        } else {
            $view['user'] = $users[0];
        }
    }

    //投稿セッションを初期化
    unset($_SESSION['post']);

    //編集開始日時を記録
    if (!empty($_GET['id'])) {
        $_SESSION['update']['user'] = localdate('Y-m-d H:i:s');
    }
}

//タイトル
if (empty($_GET['id'])) {
    $view['title'] = 'ユーザ登録';
} else {
    $view['title'] = 'ユーザ編集';
}
