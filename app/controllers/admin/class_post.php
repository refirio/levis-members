<?php

import('app/services/class.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/admin/class_form');
}

// トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['class']['id'])) {
    // 教室を登録
    $resource = service_class_insert([
        'values' => [
            'code' => $_SESSION['post']['class']['code'],
            'name' => $_SESSION['post']['class']['name'],
            'memo' => $_SESSION['post']['class']['memo'],
            'sort' => $_SESSION['post']['class']['sort'],
        ],
    ], [
        'files' => [
            'image_01' => isset($_SESSION['file']['class']['image_01']) ? $_SESSION['file']['class']['image_01'] : [],
            'image_02' => isset($_SESSION['file']['class']['image_02']) ? $_SESSION['file']['class']['image_02'] : [],
            'document' => isset($_SESSION['file']['class']['document']) ? $_SESSION['file']['class']['document'] : [],
        ],
    ]);
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    // 教室を編集
    $resource = service_class_update([
        'set'   => [
            'code' => $_SESSION['post']['class']['code'],
            'name' => $_SESSION['post']['class']['name'],
            'memo' => $_SESSION['post']['class']['memo'],
        ],
        'where' => [
            'id = :id',
            [
                'id' => $_SESSION['post']['class']['id'],
            ],
        ],
    ], [
        'id'     => intval($_SESSION['post']['class']['id']),
        'update' => $_SESSION['update']['class'],
        'files'  => [
            'image_01' => isset($_SESSION['file']['class']['image_01']) ? $_SESSION['file']['class']['image_01'] : [],
            'image_02' => isset($_SESSION['file']['class']['image_02']) ? $_SESSION['file']['class']['image_02'] : [],
            'document' => isset($_SESSION['file']['class']['document']) ? $_SESSION['file']['class']['document'] : [],
        ],
    ]);
    if (!$resource) {
        error('データを編集できません。');
    }
}

// トランザクションを終了
db_commit();

// 投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['file']);
unset($_SESSION['update']);

// リダイレクト
redirect('/admin/class?ok=post');
