<?php

//ログイン確認
if (empty($_SESSION['administrator'])) {
	error('不正なアクセスです。');
}

//対象を検証
if (!preg_match('/^image_\d\d$/', $_GET['target'])) {
	error('不正なアクセスです。');
}

$mime    = null;
$content = null;

if (empty($_SESSION['file']['class'][$_GET['target']]['delete'])) {
	if (isset($_SESSION['file']['class'][$_GET['target']]['name']) && isset($_SESSION['file']['class'][$_GET['target']]['data'])) {
		//セッションから画像を取得
		foreach (array_keys($GLOBALS['file_permissions']['image']) as $permission) {
			if (preg_match($GLOBALS['file_permissions']['image'][$permission]['regexp'], $_SESSION['file']['class'][$_GET['target']]['name'])) {
				//マイムタイプ
				$mime = $GLOBALS['file_permissions']['image'][$permission]['mime'];

				break;
			}
		}

		//コンテンツ
		$content = $_SESSION['file']['class'][$_GET['target']]['data'];
	} elseif (isset($_GET['id'])) {
		//登録内容から画像を取得
		$classes = select_classes(array(
			'where' => array(
				'id = :id',
				array(
					'id' => $_GET['id']
				)
			)
		));
		if (empty($classes)) {
			warning('データが見つかりません。');
		} else {
			$class = $classes[0];
		}

		$file = $GLOBALS['file_targets']['class'] . intval($_GET['id']) . '/' . $class[$_GET['target']];

		if (is_file($file)) {
			foreach (array_keys($GLOBALS['file_permissions']['image']) as $permission) {
				if (preg_match($GLOBALS['file_permissions']['image'][$permission]['regexp'], $class[$_GET['target']])) {
					//マイムタイプ
					$mime = $GLOBALS['file_permissions']['image'][$permission]['mime'];

					break;
				}
			}

			//コンテンツ
			$content = file_get_contents($file);
		}
	}
}

if (isset($_GET['type']) && $_GET['type'] == 'json') {
	//画像情報を取得
	if ($content == null) {
		$width  = null;
		$height = null;
	} else {
		list($width, $height) = getimagesize('data:application/octet-stream;base64,' . base64_encode($content));
	}

	header('Content-Type: application/json; charset=' . MAIN_CHARSET);

	echo json_encode(array(
		'status' => 'OK',
		'mime'   => $mime,
		'width'  => $width,
		'height' => $height,
	));
} else {
	//画像を取得
	if ($mime == null) {
		$mime = 'image/png';
	}
	if ($content == null) {
		$content = file_get_contents($GLOBALS['file_dummies']['image']);
	}

	header('Content-type: ' . $mime);

	echo $content;
}

exit;
