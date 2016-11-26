<?php

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/user/modify');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    // フォワード
    forward('/user/modify_post');
} else {
    $_view['user']    = $_SESSION['post']['user'];
    $_view['profile'] = $_SESSION['post']['profile'];
}

// タイトル
$_view['title'] = 'ユーザ情報編集確認';
