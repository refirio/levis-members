<?php

import('app/services/member.php');

// フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

// 投稿データを確認
if (empty($_SESSION['post'])) {
    // リダイレクト
    redirect('/admin/member_form');
}

// トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['member']['id'])) {
    // 名簿を登録
    $resource = service_member_insert([
        'values' => [
            'class_id'  => $_SESSION['post']['member']['class_id'],
            'name'      => $_SESSION['post']['member']['name'],
            'name_kana' => $_SESSION['post']['member']['name_kana'],
            'grade'     => $_SESSION['post']['member']['grade'],
            'birthday'  => $_SESSION['post']['member']['birthday'],
            'email'     => $_SESSION['post']['member']['email'],
            'tel'       => $_SESSION['post']['member']['tel'],
            'memo'      => $_SESSION['post']['member']['memo'],
            'public'    => $_SESSION['post']['member']['public'],
        ],
    ], [
        'category_sets' => $_SESSION['post']['member']['category_sets'],
        'files'         => [
            'image_01' => isset($_SESSION['file']['member']['image_01']) ? $_SESSION['file']['member']['image_01'] : [],
            'image_02' => isset($_SESSION['file']['member']['image_02']) ? $_SESSION['file']['member']['image_02'] : [],
        ],
    ]);
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    // 名簿を編集
    $resource = service_member_update([
        'set'  => [
            'class_id'  => $_SESSION['post']['member']['class_id'],
            'name'      => $_SESSION['post']['member']['name'],
            'name_kana' => $_SESSION['post']['member']['name_kana'],
            'grade'     => $_SESSION['post']['member']['grade'],
            'birthday'  => $_SESSION['post']['member']['birthday'],
            'email'     => $_SESSION['post']['member']['email'],
            'tel'       => $_SESSION['post']['member']['tel'],
            'memo'      => $_SESSION['post']['member']['memo'],
            'public'    => $_SESSION['post']['member']['public'],
        ],
        'where' => [
            'id = :id',
            [
                'id' => $_SESSION['post']['member']['id'],
            ],
        ],
    ], [
        'id'            => intval($_SESSION['post']['member']['id']),
        'update'        => $_SESSION['update']['member'],
        'category_sets' => $_SESSION['post']['member']['category_sets'],
        'files'         => [
            'image_01' => isset($_SESSION['file']['member']['image_01']) ? $_SESSION['file']['member']['image_01'] : [],
            'image_02' => isset($_SESSION['file']['member']['image_02']) ? $_SESSION['file']['member']['image_02'] : [],
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
redirect('/admin/member?ok=post');
