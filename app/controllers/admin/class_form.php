<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを整理
	$post = array(
		'class' => normalize_classes(array(
			'id'   => isset($_POST['id'])   ? $_POST['id']   : '',
			'code' => isset($_POST['code']) ? $_POST['code'] : '',
			'name' => isset($_POST['name']) ? $_POST['name'] : '',
			'memo' => isset($_POST['memo']) ? $_POST['memo'] : ''
		))
	);

	if (isset($_POST['preview']) && $_POST['preview'] == 'yes') {
		//プレビュー
		$view['class'] = $post['class'];
	} else {
		//入力データを検証＆登録
		$warnings = validate_classes($post['class']);
		if (isset($_POST['type']) && $_POST['type'] == 'json') {
			if (empty($warnings)) {
				ok();
			} else {
				warning($warnings);
			}
		} else {
			if (empty($warnings)) {
				$_SESSION['post']['class'] = $post['class'];

				//リダイレクト
				redirect('/admin/class_post?token=' . token('create'));
			} else {
				$view['class'] = $post['class'];

				$view['warnings'] = $warnings;
			}
		}
	}
} else {
	//初期データを取得
	if (empty($_GET['id'])) {
		$view['class'] = default_classes();

		//タイトル
		$view['title'] = '教室登録';
	} else {
		$classes = select_classes(array(
			'where' => array(
				'id = :id',
				array(
					'id' => $_GET['id']
				)
			)
		));
		if (empty($classes)) {
			warning('編集データが見つかりません。');
		} else {
			$view['class'] = $classes[0];
		}

		//タイトル
		$view['title'] = '教室編集';
	}

	if (isset($_GET['type']) && $_GET['type'] == 'json') {
		//教室情報を取得
		$files = array();

		$targets = array('image_01', 'image_02');
		foreach ($targets as $target) {
			foreach (array_keys($GLOBALS['file_permissions']['image']) as $permission) {
				if (preg_match($GLOBALS['file_permissions']['image'][$permission]['regexp'], $view['class'][$target])) {
					$files[$target] = $GLOBALS['file_permissions']['image'][$permission]['mime'];

					break;
				}
			}
			if (empty($files[$target])) {
				$files[$target] = null;
			}
		}

		header('Content-Type: application/json; charset=' . MAIN_CHARSET);

		echo json_encode(array(
			'status' => 'OK',
			'data'   => $view,
			'files'  => $files,
		));

		exit;
	} else {
		//投稿セッションを初期化
		unset($_SESSION['post']);
		unset($_SESSION['file']);
	}

	//編集開始日時を記録
	if (!empty($_GET['id'])) {
		$_SESSION['update'] = localdate('Y-m-d H:i:s');
	}
}
