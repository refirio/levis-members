<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //入力データを整理
    $post = array(
        'profile' => normalize_profiles(array(
            'user_id' => $_SESSION['user'],
            'name'    => isset($_POST['name']) ? $_POST['name'] : '',
            'text'    => isset($_POST['text']) ? $_POST['text'] : '',
        )),
    );

    //入力データを検証＆登録
    $warnings = validate_profiles($post['profile']);
    if (isset($_POST['type']) && $_POST['type'] == 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['profile'] = $post['profile'];

            //リダイレクト
            redirect('/user/profile_post?token=' . token('create'));
        } else {
            $view['profile'] = $post['profile'];

            $view['warnings'] = $warnings;
        }
    }
} else {
    //初期データを取得
    $profiles = select_profiles(array(
        'where' => array(
            'user_id = :id',
            array(
                'id' => $_SESSION['user'],
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
    $_SESSION['update'] = localdate('Y-m-d H:i:s');
}

//タイトル
$view['title'] = 'プロフィール設定';
