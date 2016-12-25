<?php

// ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

// アクセス元
if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
    error('不正なアクセスです。');
}

if (!empty($_POST['id'])) {
    // トランザクションを開始
    db_transaction();

    // ユーザを削除
    $resource = delete_users(array(
        'where' => array(
            'id = :id',
            array(
                'id' => $_POST['id'],
            ),
        ),
    ), array(
        'associate' => true,
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    // トランザクションを終了
    db_commit();

    // リダイレクト
    redirect('/admin/user?ok=delete');
} else {
    // リダイレクト
    redirect('/admin/user?warning=delete');
}
