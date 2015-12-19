<?php

import('libs/plugins/file.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//ワンタイムトークン
	if (!token('check')) {
		error('不正なアクセスです。');
	}

	if (is_uploaded_file($_FILES['file']['tmp_name']) && $_FILES['file']['type'] == 'text/csv') {
		if ($fp = fopen($_FILES['file']['tmp_name'], 'r')) {
			$options = array(
				'grades'  => array_flip($GLOBALS['options']['member']['grades']),
				'publics' => array_flip($GLOBALS['options']['member']['publics']),
			);

			//トランザクションを開始
			db_transaction();

			if ($_POST['operation'] == 'replace') {
				//元データ削除
				$resource = db_delete(array(
					'delete_from' => DATABASE_PREFIX . 'members'
				));
				if (!$resource) {
					error('データを削除できません。');
				}
			}

			//CSVファイルの一行目を無視
			$dummy = file_getcsv($fp);

			//CSVファイル読み込み
			$all_warnings = array();
			$i            = 1;
			while ($line = file_getcsv($fp)) {
				list($id, $created, $modified, $deleted, $class_id, $name, $name_kana, $grade, $birthday, $email, $tel, $memo, $image_01, $image_02, $public, $dummy) = $line;

				//入力データを整理
				$post = array(
					'class' => normalize_classes(array(
						'id'        => mb_convert_encoding($id, 'UTF-8', 'SJIS-WIN'),
						'created'   => mb_convert_encoding($created, 'UTF-8', 'SJIS-WIN'),
						'modified'  => mb_convert_encoding($modified, 'UTF-8', 'SJIS-WIN'),
						'deleted'   => mb_convert_encoding($deleted, 'UTF-8', 'SJIS-WIN'),
						'class_id'  => mb_convert_encoding($class_id, 'UTF-8', 'SJIS-WIN'),
						'name'      => mb_convert_encoding($name, 'UTF-8', 'SJIS-WIN'),
						'name_kana' => mb_convert_encoding($name_kana, 'UTF-8', 'SJIS-WIN'),
						'grade'     => $options['grades'][mb_convert_encoding($grade, 'UTF-8', 'SJIS-WIN')],
						'birthday'  => mb_convert_encoding($birthday, 'UTF-8', 'SJIS-WIN'),
						'email'     => mb_convert_encoding($email, 'UTF-8', 'SJIS-WIN'),
						'tel'       => mb_convert_encoding($tel, 'UTF-8', 'SJIS-WIN'),
						'memo'      => mb_convert_encoding($memo, 'UTF-8', 'SJIS-WIN'),
						'image_01'  => mb_convert_encoding($image_01, 'UTF-8', 'SJIS-WIN'),
						'image_02'  => mb_convert_encoding($image_02, 'UTF-8', 'SJIS-WIN'),
						'public'    => $options['publics'][mb_convert_encoding($public, 'UTF-8', 'SJIS-WIN')]
					))
				);

				//入力データを検証＆登録
				$warnings = validate_members($post['class']);
				if (empty($warnings)) {
					if ($_POST['operation'] == 'update') {
						//データ編集
						$resource = db_update(array(
							'update' => DATABASE_PREFIX . 'members',
							'set'    => array(
								'created'   => $post['class']['created'],
								'modified'  => $post['class']['modified'],
								'deleted'   => $post['class']['deleted'],
								'class_id'  => $post['class']['class_id'],
								'name'      => $post['class']['name'],
								'name_kana' => $post['class']['name_kana'],
								'grade'     => $post['class']['grade'],
								'birthday'  => $post['class']['birthday'],
								'email'     => $post['class']['email'],
								'tel'       => $post['class']['tel'],
								'memo'      => $post['class']['memo'],
								'image_01'  => $post['class']['image_01'],
								'image_02'  => $post['class']['image_02'],
								'public'    => $post['class']['public']
							),
							'where'  => array(
								'id = :id',
								array(
									'id' => $post['class']['id']
								)
							)
						));
						if (!$resource) {
							db_rollback();

							error('データを編集できません。');
						}
					} else {
						//データ登録
						$resource = db_insert(array(
							'insert_into' => DATABASE_PREFIX . 'members',
							'values'      => array(
								'id'        => $post['class']['id'],
								'created'   => $post['class']['created'],
								'modified'  => $post['class']['modified'],
								'deleted'   => $post['class']['deleted'],
								'class_id'  => $post['class']['class_id'],
								'name'      => $post['class']['name'],
								'name_kana' => $post['class']['name_kana'],
								'grade'     => $post['class']['grade'],
								'birthday'  => $post['class']['birthday'],
								'email'     => $post['class']['email'],
								'tel'       => $post['class']['tel'],
								'memo'      => $post['class']['memo'],
								'image_01'  => $post['class']['image_01'],
								'image_02'  => $post['class']['image_02'],
								'public'    => $post['class']['public']
							)
						));
						if (!$resource) {
							db_rollback();

							error('データを登録できません。');
						}
					}
				} else {
					foreach ($warnings as $warning) {
						$all_warnings[] = '[' . $i . '行目] ' . $warning;
					}
				}

				$i++;
			}

			if (empty($all_warnings)) {
				//トランザクションを終了
				db_commit();

				redirect('/admin/csv_upload?ok=post');
			} else {
				$view['warnings'] = $all_warnings;

				//ロールバック
				db_rollback();
			}

			fclose($fp);
		} else {
			$view['warnings'] = array('ファイルを読み込めません。');
		}
	} else {
		$view['warnings'] = array('CSVファイルを選択してください。');
	}
}
