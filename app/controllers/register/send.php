<?php

//暗証コードを確認
if (empty($_SESSION['token_code'])) {
    //リダイレクト
    redirect('/register');
}

//タイトル
$view['title'] = 'ユーザ登録';
