<?php

import('app/services/profile.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/admin/user_form');
}

// トランザクションを開始
db_transaction();

// ユーザを編集
$resource = service_profile_update([
    'set'   => [
        'name' => $_SESSION['post']['profile']['name'],
        'text' => $_SESSION['post']['profile']['text'],
        'memo' => $_SESSION['post']['profile']['memo'],
    ],
    'where' => [
        'id = :id',
        [
            'id' => $_SESSION['post']['profile']['id'],
        ],
    ],
], [
    'id'     => intval($_SESSION['post']['profile']['id']),
    'update' => $_SESSION['update']['profile'],
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
redirect('/admin/user?ok=post');
