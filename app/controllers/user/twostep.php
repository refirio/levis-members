<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力データを整理
    $post = array(
        'user' => normalize_users(array(
            'id'            => $_SESSION['auth']['user']['id'],
            'twostep'       => isset($_POST['twostep'])       ? $_POST['twostep']       : '',
            'twostep_email' => isset($_POST['twostep_email']) ? $_POST['twostep_email'] : '',
        )),
    );

    // 入力データを検証＆登録
    $warnings = validate_users($post['user']);
    if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['user'] = $post['user'];

            // フォワード
            forward('/user/twostep_post');
        } else {
            $_view['user'] = $post['user'];

            $_view['warnings'] = $warnings;
        }
    }
} else {
    // 初期データを取得
    $users = select_users(array(
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
    }

    // 投稿セッションを初期化
    unset($_SESSION['post']);

    // 編集開始日時を記録
    $_SESSION['update']['user'] = localdate('Y-m-d H:i:s');
}

// ユーザの表示用データ作成
$_view['user'] = view_users($_view['user']);

// タイトル
$_view['title'] = '2段階認証設定';
