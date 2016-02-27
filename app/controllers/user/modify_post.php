<?php

import('libs/plugins/hash.php');

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/user/modify');
}

//パスワードのソルトを作成
$password_salt = hash_salt();

//トランザクションを開始
db_transaction();

//ユーザを編集
$sets = array(
    'username' => $_SESSION['post']['user']['username'],
    'email'    => $_SESSION['post']['user']['email'],
);
if (!empty($_SESSION['post']['user']['password'])) {
    $sets['password']      = hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['hash_salt']);
    $sets['password_salt'] = $password_salt;
}
$resource = update_users(array(
    'set'   => $sets,
    'where' => array(
        'id = :id AND regular = 1',
        array(
            'id' => $_SESSION['user']['id'],
        ),
    ),
), array(
    'id'     => intval($_SESSION['user']['id']),
    'update' => $_SESSION['update'],
));
if (!$resource) {
    error('データを編集できません。');
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

//リダイレクト
redirect('/user/modify_complete');
