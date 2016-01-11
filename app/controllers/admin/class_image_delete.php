<?php

//対象を検証
if (!preg_match('/^image_\d\d$/', $_GET['target'])) {
	error('不正なアクセスです。');
}

//ワンタイムトークン
if (!token('check')) {
	error('不正なアクセスです。');
}

//画像を削除
$_SESSION['file'][$_GET['target']]['delete'] = true;

if (isset($_GET['type']) && $_GET['type'] == 'json') {
	ok();
} else {
	//リダイレクト
	redirect('/admin/class_image_upload?ok=delete&target=' . $_GET['target'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
}
