<?php

//対象を検証
if (!preg_match('/^[\w\-]+$/', $_GET['target'])) {
    error('不正なアクセスです。');
}
if (!preg_match('/^[\w\-]+$/', $_GET['key'])) {
    error('不正なアクセスです。');
}

//形式を検証
if (!preg_match('/^[\w\-]+$/', $_GET['format'])) {
    error('不正なアクセスです。');
}

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//画像を削除
$_SESSION['file'][$_GET['target']][$_GET['key']]['delete'] = true;

if (isset($_GET['type']) && $_GET['type'] == 'json') {
    ok();
} else {
    //リダイレクト
    redirect('/admin/file_upload?ok=delete&target=' . $_GET['target'] . '&key=' . $_GET['key'] . '&format=' . $_GET['format'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
}
