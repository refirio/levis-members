<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if ((empty($_POST['view']) || $_POST['view'] !== 'preview') && !token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'profile' => normalize_profiles(array(
            'id'   => isset($_POST['id'])   ? $_POST['id']   : '',
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'text' => isset($_POST['text']) ? $_POST['text'] : '',
            'memo' => isset($_POST['memo']) ? $_POST['memo'] : '',
        )),
    );

    if (isset($_POST['view']) && $_POST['view'] === 'preview') {
        //プレビュー
        $view['profile'] = $post['profile'];
    } else {
        //入力データを検証＆登録
        $warnings = validate_profiles($post['profile']);
        if (isset($_POST['type']) && $_POST['type'] === 'json') {
            if (empty($warnings)) {
                ok();
            } else {
                warning($warnings);
            }
        } else {
            if (empty($warnings)) {
                $_SESSION['post']['profile'] = $post['profile'];

                //フォワード
                forward('/admin/profile_post');
            } else {
                $view['profile'] = $post['profile'];

                $view['warnings'] = $warnings;
            }
        }
    }
} else {
    //初期データを取得
    $profiles = select_profiles(array(
        'where' => array(
            'user_id = :user_id',
            array(
                'user_id' => $_GET['user_id'],
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
    if (!empty($_GET['user_id'])) {
        $_SESSION['update']['profile'] = localdate('Y-m-d H:i:s');
    }
}

//タイトル
$view['title'] = 'プロフィール編集';
