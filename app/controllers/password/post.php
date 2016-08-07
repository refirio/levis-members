<?php

import('libs/plugins/hash.php');

//フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/password');
}

//トランザクションを開始
db_transaction();

//パスワードのソルトを作成
$password_salt = hash_salt();

//ユーザを編集
$resource = update_users(array(
    'set'   => array(
        'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']),
        'password_salt' => $password_salt,
        'token'         => null,
        'token_code'    => null,
        'token_expire'  => null,
    ),
    'where' => array(
        'email = :email AND regular = 1',
        array(
            'email' => $_SESSION['post']['user']['key'],
        ),
    ),
), array(
    'id'     => intval($_SESSION['post']['user']['id']),
    'update' => $_SESSION['update']['user'],
));
if (!$resource) {
    error('データを編集できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);
unset($_SESSION['expect']);

//リダイレクト
redirect('/password/complete');
