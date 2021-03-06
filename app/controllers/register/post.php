<?php

import('libs/plugins/hash.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/register');
}

// パスワードのソルトを作成
$password_salt = hash_salt();

// トランザクションを開始
db_transaction();

// ユーザを登録
$resource = service_user_insert(array(
    'values' => array(
        'username'      => $_SESSION['post']['user']['username'],
        'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']),
        'password_salt' => $password_salt,
        'email'         => $_SESSION['post']['user']['email'],
    ),
));
if (!$resource) {
    error('データを登録できません。');
}

// IDを取得
$user_id = db_last_insert_id();

// プロフィールを登録
$resource = service_profile_insert(array(
    'values' => array(
        'user_id' => $user_id,
    ),
));
if (!$resource) {
    error('データを登録できません。');
}

// トランザクションを終了
db_commit();

// 投稿セッションを初期化
unset($_SESSION['post']);

// リダイレクト
redirect('/register/complete');
