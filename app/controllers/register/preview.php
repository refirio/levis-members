<?php

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/register');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $warnings = array();

    // ワンタイムトークン
    if (!token('check')) {
        $warnings[] = '不正な操作が検出されました。送信内容を確認して再度実行してください。';
    }

    // アクセス元
    if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
        $warnings[] = '不正なアクセスです。';
    }

    if (empty($warnings)) {
        // フォワード
        forward('/register/post');
    } else {
        $_view['warnings'] = $warnings;
    }
}

$_view['user'] = $_SESSION['post']['user'];

// タイトル
$_view['title'] = 'ユーザ登録確認';
