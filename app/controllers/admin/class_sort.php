<?php

if (isset($_POST['sort'])) {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//並び順を更新
	list($success, $message) = class_sort($_POST['sort']);
	if ($success == 0) {
		error($message);
	}

	if (isset($_POST['type']) && $_POST['type'] == 'json') {
		header('Content-Type: application/json; charset=' . MAIN_CHARSET);
		echo json_encode(array('status' => 'OK'));
		exit;
	} else {
		redirect('/admin/class?ok=sort');
	}
} else {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//移動
	list($success, $message) = class_move($_GET['id'], $_GET['target']);
	if ($success == 0) {
		error($message);
	}

	redirect('/admin/class?ok=sort');
}
