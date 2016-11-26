<?php

// 暗証コードを確認
if (empty($_SESSION['expect'])) {
    // リダイレクト
    redirect('/register');
}

// タイトル
$_view['title'] = 'ユーザ登録';
