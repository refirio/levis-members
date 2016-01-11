<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	//入力データを整理
	$post = array(
		'user' => normalize_users(array(
			'id'               => $_SESSION['user'],
			'username'         => isset($_POST['username'])         ? $_POST['username']         : '',
			'password'         => isset($_POST['password'])         ? $_POST['password']         : '',
			'password_confirm' => isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
			'name'             => isset($_POST['name'])             ? $_POST['name']             : '',
			'email'            => isset($_POST['email'])            ? $_POST['email']            : '',
			'memo'             => isset($_POST['memo'])             ? $_POST['memo']             : ''
		))
	);

	//入力データを検証＆登録
	$warnings = validate_users($post['user']);
	if (isset($_POST['type']) && $_POST['type'] == 'json') {
		if (empty($warnings)) {
			ok();
		} else {
			warning($warnings);
		}
	} else {
		if (empty($warnings)) {
			$_SESSION['post']['user'] = $post['user'];

			//リダイレクト
			redirect('/user/modify_preview');
		} else {
			$view['user'] = $post['user'];

			$view['warnings'] = $warnings;
		}
	}
} elseif (isset($_GET['referer']) && $_GET['referer'] == 'preview') {
	//入力データを復元
	$view['user'] = $_SESSION['post']['user'];
} else {
	//初期データを取得
	$users = select_users(array(
		'where' => array(
			'id = :id',
			array(
				'id' => $_SESSION['user']
			)
		)
	));
	if (empty($users)) {
		warning('編集データが見つかりません。');
	} else {
		$view['user'] = $users[0];

		$view['user']['password'] = '';
	}

	//投稿セッションを初期化
	unset($_SESSION['post']);

	//編集開始日時を記録
	$_SESSION['update'] = localdate('Y-m-d H:i:s');
}

//タイトル
$view['title'] = 'ユーザ情報編集';
