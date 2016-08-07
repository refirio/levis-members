<?php

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/user/modify');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //フォワード
    forward('/user/modify_post');
} else {
    $view['user']    = $_SESSION['post']['user'];
    $view['profile'] = $_SESSION['post']['profile'];
}

//タイトル
$view['title'] = 'ユーザ情報編集確認';
