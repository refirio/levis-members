<?php

import('libs/plugins/hash.php');

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/register');
}

//パスワードのソルトを作成
$password_salt = hash_salt();

//トランザクションを開始
db_transaction();

//ユーザを編集
$resource = update_users(array(
    'set'   => array(
        'username'      => $_SESSION['post']['user']['username'],
        'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']),
        'password_salt' => $password_salt,
        'regular'       => 1,
        'token'         => null,
        'token_code'    => null,
        'token_expire'  => null,
    ),
    'where' => array(
        'email = :email AND regular = 0',
        array(
            'email' => $_SESSION['post']['user']['key'],
        ),
    ),
));
if (!$resource) {
    error('データを登録できません。');
}

//仮ユーザ情報を取得
$users = select_users(array(
    'where' => array(
        'email = :email AND regular = 1',
        array(
            'email' => $_SESSION['post']['user']['key'],
        ),
    ),
));

//IDを取得
$id = $users[0]['id'];

//プロフィールを登録
$resource = insert_profiles(array(
    'values' => array(
        'user_id' => $id,
    ),
));
if (!$resource) {
    error('データを登録できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['token_code']);

//リダイレクト
redirect('/register/complete');
