<?php

//対象を検証
if (!preg_match('/^image_\d\d$/', $_GET['target'])) {
	error('不正なアクセスです。');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//コンテンツ
	$content = null;
	if (isset($_SESSION['file']['class'][$_GET['target']]['name']) && isset($_SESSION['file']['class'][$_GET['target']]['data'])) {
		$content = $_SESSION['file']['class'][$_GET['target']]['data'];
	} elseif (isset($_GET['id'])) {
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
			$class = $classes[0];
		}

		$file = $GLOBALS['file_targets']['class'] . intval($_GET['id']) . '/' . $class[$_GET['target']];

		if (is_file($file)) {
			$content = file_get_contents($file);
		}
	}

	//選択範囲
	$trimming_left   = intval($_POST['trimming']['left']);
	$trimming_top    = intval($_POST['trimming']['top']);
	$trimming_width  = intval($_POST['trimming']['width']);
	$trimming_height = intval($_POST['trimming']['height']);

	$image = imagecreatetruecolor($trimming_width, $trimming_height);

	//トリミング
	$temporary_file = $GLOBALS['file_targets']['class'] . session_id();
	if ($image && imagecopyresampled($image, imagecreatefromstring($content), 0, 0, $trimming_left, $trimming_top, $trimming_width, $trimming_height, $trimming_width, $trimming_height)) {
		imagepng($image, $temporary_file);
	} else {
		warning('編集できません。');
	}

	$_SESSION['file']['class'][$_GET['target']] = array(
		'name' => 'process.png',
		'data' => file_get_contents($temporary_file)
	);

	unlink($temporary_file);

	redirect('/admin/class_image_process?ok=post&target=' . $_GET['target'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
}

//初期データを取得
$view['id']     = isset($_GET['id']) ? $_GET['id'] : '';
$view['target'] = $_GET['target'];