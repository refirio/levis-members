<?php

import('libs/plugins/hash.php');

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/user/twostep');
}

//トランザクションを開始
db_transaction();

//ユーザを編集
$resource = update_users(array(
    'set'   => array(
        'twostep'       => $_SESSION['post']['user']['twostep'],
        'twostep_email' => $_SESSION['post']['user']['twostep_email'],
    ),
    'where' => array(
        'id = :id',
        array(
            'id' => $_SESSION['user'],
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
redirect('/user/twostep_complete');
