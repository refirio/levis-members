<?php

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

// ユーザを削除
$resource = service_user_delete(array(
    'where' => array(
        'id = :id',
        array(
            'id' => $_SESSION['auth']['user']['id'],
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

// 認証セッションを初期化
unset($_SESSION['auth']['user']);

// リダイレクト
redirect('/user/delete_complete');
