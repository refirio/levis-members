<?php

import('app/services/user.php');
import('app/services/profile.php');
import('libs/plugins/hash.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/user/modify');
}

// パスワードのソルトを作成
$password_salt = hash_salt();

// トランザクションを開始
db_transaction();

// メールアドレスを取得
$users = select_users(array(
    'select' => 'email',
    'where'  => array(
        'id = :id',
        array(
            'id' => $_SESSION['auth']['user']['id'],
        ),
    ),
));

// メールアドレスの変更を確認
if ($_SESSION['post']['user']['email'] === $users[0]['email']) {
    $email_verified = 1;
} else {
    $email_verified = 0;
}

// ユーザを編集
$sets = array(
    'username'       => $_SESSION['post']['user']['username'],
    'email'          => $_SESSION['post']['user']['email'],
    'email_verified' => $email_verified,
);
if (!empty($_SESSION['post']['user']['password'])) {
    $sets['password']      = hash_crypt($_SESSION['post']['user']['password'], $password_salt . ':' . $GLOBALS['config']['hash_salt']);
    $sets['password_salt'] = $password_salt;
}
$resource = service_user_update(array(
    'set'   => $sets,
    'where' => array(
        'id = :id',
        array(
            'id' => $_SESSION['auth']['user']['id'],
        ),
    ),
), array(
    'id'     => intval($_SESSION['auth']['user']['id']),
    'update' => $_SESSION['update']['user'],
));
if (!$resource) {
    error('データを編集できません。');
}

// プロフィールを編集
$resource = service_profile_update(array(
    'set'   => array(
        'name' => $_SESSION['post']['profile']['name'],
        'text' => $_SESSION['post']['profile']['text'],
    ),
    'where' => array(
        'user_id = :user_id',
        array(
            'user_id' => $_SESSION['auth']['user']['id'],
        ),
    ),
), array(
    'id'     => intval($_SESSION['auth']['user']['id']),
    'update' => $_SESSION['update']['user'],
));
if (!$resource) {
    error('データを編集できません。');
}

// トランザクションを終了
db_commit();

// 投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

// リダイレクト
redirect('/user/modify_complete');
