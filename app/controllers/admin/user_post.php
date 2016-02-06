<?php

import('libs/plugins/hash.php');

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/admin/user_form');
}

//パスワードのソルトを作成
$password_salt = hash_salt();

//トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['user']['id'])) {
    //ユーザを登録
    $resource = insert_users(array(
        'values' => array(
            'username'      => $_SESSION['post']['user']['username'],
            'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['hash_salt']),
            'password_salt' => $password_salt,
            'name'          => $_SESSION['post']['user']['name'],
            'email'         => $_SESSION['post']['user']['email'],
            'memo'          => $_SESSION['post']['user']['memo'],
            'twostep'       => 0
        )
    ));
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    //ユーザを編集
    $sets = array(
        'username' => $_SESSION['post']['user']['username'],
        'name'     => $_SESSION['post']['user']['name'],
        'email'    => $_SESSION['post']['user']['email'],
        'memo'     => $_SESSION['post']['user']['memo']
    );
    if (!empty($_SESSION['post']['user']['password'])) {
        $sets['password']      = hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['hash_salt']);
        $sets['password_salt'] = $password_salt;
    }
    $resource = update_users(array(
        'set'   => $sets,
        'where' => array(
            'id = :id',
            array(
                'id' => $_SESSION['post']['user']['id']
            )
        )
    ), array(
        'id'     => intval($_SESSION['post']['user']['id']),
        'update' => $_SESSION['update']
    ));
    if (!$resource) {
        error('データを編集できません。');
    }
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

//リダイレクト
redirect('/admin/user?ok=post');
