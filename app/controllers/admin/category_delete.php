<?php

import('app/services/category.php');

// ワンタイムトークン
if (!token('check')) {
    error('不正な操作が検出されました。送信内容を確認して再度実行してください。');
}

// アクセス元
if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
    error('不正なアクセスです。');
}

// トランザクションを開始
db_transaction();

// 分類を削除
$resource = service_category_delete(array(
    'where' => array(
        'categories.id = :id',
        array(
            'id' => $_POST['id'],
        ),
    ),
), array(
    'associate' => 'true',
));
if (!$resource) {
    error('データを削除できません。');
}

// トランザクションを終了
db_commit();

// リダイレクト
redirect('/admin/category?ok=delete');
