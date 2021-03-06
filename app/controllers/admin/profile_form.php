<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if ((empty($_POST['view']) || $_POST['view'] !== 'preview') && !token('check')) {
        error('不正な操作が検出されました。送信内容を確認して再度実行してください。');
    }

    // アクセス元
    if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
        error('不正なアクセスです。');
    }

    // 入力データを整理
    $post = array(
        'profile' => normalize_profiles(array(
            'id'   => isset($_POST['id'])   ? $_POST['id']   : '',
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'text' => isset($_POST['text']) ? $_POST['text'] : '',
            'memo' => isset($_POST['memo']) ? $_POST['memo'] : '',
        )),
    );

    if (isset($_POST['view']) && $_POST['view'] === 'preview') {
        // プレビュー
        $_view['profile'] = $post['profile'];
    } else {
        // 入力データを検証＆登録
        $warnings = validate_profiles($post['profile']);
        if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
            if (empty($warnings)) {
                ok();
            } else {
                warning($warnings);
            }
        } else {
            if (empty($warnings)) {
                $_SESSION['post']['profile'] = $post['profile'];

                // フォワード
                forward('/admin/profile_post');
            } else {
                $_view['profile'] = $post['profile'];

                $_view['warnings'] = $warnings;
            }
        }
    }
} else {
    // 初期データを取得
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
        $_view['profile'] = $profiles[0];
    }

    // 投稿セッションを初期化
    unset($_SESSION['post']);

    // 編集開始日時を記録
    if (!empty($_GET['user_id'])) {
        $_SESSION['update']['profile'] = localdate('Y-m-d H:i:s');
    }
}

// タイトル
$_view['title'] = 'プロフィール編集';
