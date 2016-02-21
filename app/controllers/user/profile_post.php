<?php

import('libs/plugins/hash.php');

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/user/profile');
}

//トランザクションを開始
db_transaction();

//ユーザを編集
$resource = update_profiles(array(
    'set'   => array(
        'name' => $_SESSION['post']['profile']['name'],
        'text' => $_SESSION['post']['profile']['text'],
    ),
    'where' => array(
        'user_id = :user_id',
        array(
            'user_id' => $_SESSION['user'],
        ),
    ),
), array(
    'id'     => intval($_SESSION['user']),
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
redirect('/user/profile_complete');
