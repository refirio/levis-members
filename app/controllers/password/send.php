<?php

//暗証コードを確認
if (empty($_SESSION['token_code'])) {
    //リダイレクト
    redirect('/password');
}

//タイトル
$view['title'] = 'パスワード再発行';
