<?php

import('libs/plugins/file.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    // アクセス元
    if (empty($_SERVER['HTTP_REFERER']) || !preg_match('/^' . preg_quote($GLOBALS['config']['http_url'], '/') . '/', $_SERVER['HTTP_REFERER'])) {
        error('不正なアクセスです。');
    }

    if (is_uploaded_file($_FILES['file']['tmp_name']) && preg_match('/\.csv$/i', $_FILES['file']['name'])) {
        // トランザクションを開始
        db_transaction();

        // 名簿をCSV形式で入力
        $warnings = service_member_import($_FILES['file']['tmp_name']);
        if (empty($warnings)) {
            // トランザクションを終了
            db_commit();

            // リダイレクト
            redirect('/admin/csv_upload?ok=post');
        } else {
            // トランザクションをロールバック
            db_rollback();

            $_view['warnings'] = $warnings;
        }
    } else {
        $_view['warnings'] = array('CSVファイルを選択してください。');
    }
}

// タイトル
$_view['title'] = 'CSVアップロード';
