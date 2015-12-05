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

	//入力データを検証＆登録
	if (isset($_POST['type']) && $_POST['type'] == 'json') {
		if (count($_FILES['files']['tmp_name']) > 1) {
			error('アップロードできるファイルは1つです。');
		} else {
			$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
			$_FILES['file']['name']     = $_FILES['files']['name'][0];
		}
	}

	if (is_uploaded_file($_FILES['file']['tmp_name'])) {
		$names = array();
		$ext   = null;
		foreach (array_keys($GLOBALS['file_permissions']['image']) as $permission) {
			$names[] = $GLOBALS['file_permissions']['image'][$permission]['name'];

			if (preg_match($GLOBALS['file_permissions']['image'][$permission]['regexp'], $_FILES['file']['name'])) {
				$ext = $GLOBALS['file_permissions']['image'][$permission]['ext'];

				break;
			}
		}

		if ($ext == null) {
			$view['warnings'] = array('アップロードできるファイル形式は' . implode('、', $names) . 'のみです。');
		} else {
			$_SESSION['file']['member'][$_GET['target']] = array(
				'name' => $_FILES['file']['name'],
				'data' => file_get_contents($_FILES['file']['tmp_name'])
			);

			if (isset($_FILES['files'])) {
				ok();
			} else {
				redirect('/admin/member_image_upload?ok=post&target=' . $_GET['target'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
			}
		}
	} else {
		$view['warnings'] = array('ファイルを選択してください。');
	}
}

//初期データを取得
if (empty($view['warnings'])) {
	if (isset($_SESSION['file']['member'][$_GET['target']]['data'])) {
		$file = true;
	} elseif (isset($_GET['id'])) {
		$members = select_members(array(
			'where' => array(
				'id = :id',
				array(
					'id' => $_GET['id']
				)
			)
		));
		if (empty($members)) {
			warning('編集データが見つかりません。');
		} else {
			$member = $members[0];
		}

		$file = $member[$_GET['target']] ? true : false;
	} else {
		$file = false;
	}

	if (isset($_POST['type']) && $_POST['type'] == 'json') {
		ok();
	}
} else{
	if (isset($_POST['type']) && $_POST['type'] == 'json') {
		error($view['warnings'][0]);
	}
}

$view['target'] = $_GET['target'];
$view['file']   = $file;
