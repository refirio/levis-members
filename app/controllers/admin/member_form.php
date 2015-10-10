<?php

import('libs/plugins/ui.php');

//ログイン確認
if (empty($_SESSION['administrator'])) {
	redirect('/admin');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを整理
	$post = array(
		'member' => normalize_members(array(
			'id'        => isset($_POST['id'])        ? $_POST['id']        : '',
			'class_id'  => isset($_POST['class_id'])  ? $_POST['class_id']  : '',
			'name'      => isset($_POST['name'])      ? $_POST['name']      : '',
			'name_kana' => isset($_POST['name_kana']) ? $_POST['name_kana'] : '',
			'grade'     => isset($_POST['grade'])     ? $_POST['grade']     : '',
			'birthday'  => isset($_POST['birthday'])  ? $_POST['birthday']  : '',
			'email'     => isset($_POST['email'])     ? $_POST['email']     : '',
			'tel'       => isset($_POST['tel'])       ? $_POST['tel']       : '',
			'memo'      => isset($_POST['memo'])      ? $_POST['memo']      : '',
			'public'    => isset($_POST['public'])    ? $_POST['public']    : ''
		))
	);

	if (isset($_POST['preview']) && $_POST['preview'] == 'yes') {
		//プレビュー
		$view['member'] = $post['member'];
	} else {
		//入力データを検証＆登録
		$warnings = validate_members($post['member']);
		if (isset($_POST['type']) && $_POST['type'] == 'json') {
			if (empty($warnings)) {
				ok();
			} else {
				warning($warnings);
			}
		} else {
			if (empty($warnings)) {
				$_SESSION['post']['member'] = $post['member'];

				redirect('/admin/member_post?token=' . token('create'));
			} else {
				$view['member'] = $post['member'];

				$view['warnings'] = $warnings;
			}
		}
	}
} else {
	//初期データを取得
	if (empty($_GET['id'])) {
		$view['member'] = default_members();
	} else {
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
			$view['member'] = $members[0];
		}
	}

	if (isset($_GET['type']) && $_GET['type'] == 'json') {
		//教室情報を取得
		$files = array();

		$targets = array('image_01', 'image_02');
		foreach ($targets as $target) {
			foreach (array_keys($GLOBALS['file_permissions']['image']) as $permission) {
				if (preg_match($GLOBALS['file_permissions']['image'][$permission]['regexp'], $view['member'][$target])) {
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

if (empty($_POST['preview']) || $_POST['preview'] == 'no') {
	//名簿のフォーム用データ作成
	$view['member'] = form_members($view['member']);
}

//教室を取得
$view['classes'] = select_classes(array(
	'order_by' => 'sort, id'
));
