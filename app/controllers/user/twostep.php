<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //入力データを整理
    $post = array(
        'user' => normalize_users(array(
            'id'            => $_SESSION['user']['id'],
            'twostep'       => isset($_POST['twostep'])       ? $_POST['twostep']       : '',
            'twostep_email' => isset($_POST['twostep_email']) ? $_POST['twostep_email'] : '',
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
            redirect('/user/twostep_post?token=' . token('create'));
        } else {
            $view['user'] = $post['user'];

            $view['warnings'] = $warnings;
        }
    }
} else {
    //初期データを取得
    $users = select_users(array(
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['user']['id'],
            ),
        ),
    ));
    if (empty($users)) {
        warning('編集データが見つかりません。');
    } else {
        $view['user'] = $users[0];
    }

    //投稿セッションを初期化
    unset($_SESSION['post']);

    //編集開始日時を記録
    $_SESSION['update'] = localdate('Y-m-d H:i:s');
}

//ユーザの表示用データ作成
$view['user'] = view_users($view['user']);

//タイトル
$view['title'] = '2段階認証設定';
