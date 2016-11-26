<?php

// 暗証コードを確認
if (empty($_SESSION['expect'])) {
    // リダイレクト
    redirect('/password');
}

// タイトル
$_view['title'] = 'パスワード再発行';
