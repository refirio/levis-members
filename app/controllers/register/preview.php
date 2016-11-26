<?php

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/register');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    // フォワード
    forward('/register/post');
} else {
    $_view['user'] = $_SESSION['post']['user'];
}

// タイトル
$_view['title'] = 'ユーザ登録確認';
