<?php

import('libs/plugins/file.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	if (is_uploaded_file($_FILES['file']['tmp_name']) && preg_match('/\.csv$/i', $_FILES['file']['name'])) {
		//トランザクションを開始
		db_transaction();

		//名簿をCSV形式で入力
		$warnings = member_import($_FILES['file']['tmp_name']);
		if (empty($warnings)) {
			//トランザクションを終了
			db_commit();

			redirect('/admin/csv_upload?ok=post');
		} else {
			//トランザクションをロールバック
			db_rollback();

			$view['warnings'] = $warnings;
		}
	} else {
		$view['warnings'] = array('CSVファイルを選択してください。');
	}
}
