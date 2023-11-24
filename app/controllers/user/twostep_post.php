<?php

import('app/services/user.php');
import('libs/plugins/hash.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/user/twostep');
}

// トランザクションを開始
db_transaction();

// ユーザを編集
$resource = service_user_update([
    'set'   => [
        'twostep'       => $_SESSION['post']['user']['twostep'],
        'twostep_email' => $_SESSION['post']['user']['twostep_email'],
    ],
    'where' => [
        'id = :id',
        [
            'id' => $_SESSION['auth']['user']['id'],
        ],
    ],
], [
    'id'     => intval($_SESSION['auth']['user']['id']),
    'update' => $_SESSION['update']['user'],
]);
if (!$resource) {
    error('データを編集できません。');
}

// トランザクションを終了
db_commit();

// 投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

// リダイレクト
redirect('/user/twostep_complete');
