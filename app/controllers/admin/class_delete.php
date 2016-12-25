<?php

// ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

// アクセス元
if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
    error('不正なアクセスです。');
}

// トランザクションを開始
db_transaction();

// 教室を削除
$resource = delete_classes(array(
    'where' => array(
        'classes.id = :id',
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
redirect('/admin/class?ok=delete');
