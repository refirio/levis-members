<?php

import('libs/plugins/hash.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/admin/user_form');
}

// パスワードのソルトを作成
$password_salt = hash_salt();

// トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['user']['id'])) {
    // ユーザを登録
    $resource = insert_users(array(
        'values' => array(
            'username'      => $_SESSION['post']['user']['username'],
            'password'      => hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']),
            'password_salt' => $password_salt,
            'regular'       => 1,
            'email'         => $_SESSION['post']['user']['email'],
        ),
    ));
    if (!$resource) {
        error('データを登録できません。');
    }

    // IDを取得
    $id = db_last_insert_id();

    // プロフィールを登録
    $resource = insert_profiles(array(
        'values' => array(
            'user_id' => $id,
        ),
    ));
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    // ユーザを編集
    $sets = array(
        'username' => $_SESSION['post']['user']['username'],
        'email'    => $_SESSION['post']['user']['email'],
    );
    if (!empty($_SESSION['post']['user']['password'])) {
        $sets['password']      = hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']);
        $sets['password_salt'] = $password_salt;
    }
    $resource = update_users(array(
        'set'   => $sets,
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_SESSION['post']['user']['id'],
            ),
        ),
    ), array(
        'id'     => intval($_SESSION['post']['user']['id']),
        'update' => $_SESSION['update']['user'],
    ));
    if (!$resource) {
        error('データを編集できません。');
    }
}

// トランザクションを終了
db_commit();

// 投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

// リダイレクト
redirect('/admin/user?ok=post');
