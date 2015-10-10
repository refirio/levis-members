<?php

if (!empty($_SESSION['user'])) {
	//ユーザ情報を取得
	$users = select_users(array(
		'where' => array(
			'id = :id',
			array(
				'id' => $_SESSION['user']
			)
		)
	));
	if (empty($users)) {
		unset($_SESSION['user']);

		redirect('/user');
	} else {
		$view['_user'] = $users[0];
	}
}
