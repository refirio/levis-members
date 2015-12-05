<?php

import('libs/plugins/file.php');
import('libs/plugins/directory.php');

/*
 * 名簿の取得
 */
function select_members($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'associate' => isset($options['associate']) ? $options['associate'] : false
	);

	if ($options['associate'] == true) {
		//関連するデータを取得
		if (!isset($queries['select'])) {
			$queries['select'] = 'members.*, '
			                   . 'classes.name AS class_name';
		}

		$queries['from'] = DATABASE_PREFIX . 'members AS members '
		                 . 'LEFT JOIN '
		                 . DATABASE_PREFIX . 'classes AS classes '
		                 . 'ON '
		                 . 'members.class_id = classes.id';

		//削除済みデータは取得しない
		if (!isset($queries['where'])) {
			$queries['where'] = 'TRUE';
		}
		$queries['where'] = 'members.deleted IS NULL AND (' . $queries['where'] . ')';
	} else {
		//名簿を取得
		$queries['from'] = DATABASE_PREFIX . 'members';

		//削除済みデータは取得しない
		if (!isset($queries['where'])) {
			$queries['where'] = 'TRUE';
		}
		$queries['where'] = 'deleted IS NULL AND (' . $queries['where'] . ')';
	}

	//データを取得
	$results = db_select($queries);

	return $results;
}

/*
 * 名簿の登録
 */
function insert_members($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'files' => isset($options['files']) ? $options['files'] : array()
	);

	//初期値を取得
	$defaults = default_classes();

	if (isset($queries['values']['created'])) {
		if ($queries['values']['created'] === false) {
			unset($queries['values']['created']);
		}
	} else {
		$queries['values']['created'] = $defaults['created'];
	}
	if (isset($queries['values']['modified'])) {
		if ($queries['values']['modified'] === false) {
			unset($queries['values']['modified']);
		}
	} else {
		$queries['values']['modified'] = $defaults['modified'];
	}

	//データを登録
	$queries['insert_into'] = DATABASE_PREFIX . 'members';

	$resource = db_insert($queries);

	if (!empty($options['files'])) {
		//IDを取得
		$id = db_last_insert_id();

		//関連するファイルを削除
		remove_members($id, $options['files']);

		//関連するファイルを保存
		save_members($id, $options['files']);
	}

	return $resource;
}

/*
 * 名簿の編集
 */
function update_members($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'id'     => isset($options['id'])     ? $options['id']     : null,
		'update' => isset($options['update']) ? $options['update'] : null,
		'files'  => isset($options['files'])  ? $options['files']  : array()
	);

	//最終編集日時を確認
	if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
		$members = db_select(array(
			'from'  => DATABASE_PREFIX . 'members',
			'where' => array(
				'id = :id AND modified > :update',
				array(
					'id'     => $options['id'],
					'update' => $options['update']
				)
			)
		));
		if (!empty($members)) {
			error('編集開始後にデータが更新されています。');
		}
	}

	//初期値を取得
	$defaults = default_members();

	if (isset($queries['set']['modified'])) {
		if ($queries['set']['modified'] === false) {
			unset($queries['set']['modified']);
		}
	} else {
		$queries['set']['modified'] = $defaults['modified'];
	}

	//データを編集
	$queries['update'] = DATABASE_PREFIX . 'members';

	$resource = db_update($queries);

	if (!empty($options['files'])) {
		//IDを取得
		$id = $options['id'];

		//関連するファイルを削除
		remove_members($id, $options['files']);

		//関連するファイルを保存
		save_members($id, $options['files']);
	}

	return $resource;
}

/*
 * 名簿の削除
 */
function delete_members($queries, $options = array())
{
	$queries = db_placeholder($queries);
	$options = array(
		'file' => isset($options['file']) ? $options['file'] : false
	);

	//削除するデータのIDを取得
	$members = db_select(array(
		'select' => 'id',
		'from'   => DATABASE_PREFIX . 'members',
		'where'  => isset($queries['where']) ? $queries['where'] : '',
		'limit'  => isset($queries['limit']) ? $queries['limit'] : ''
	));

	$deletes = array();
	foreach ($members as $member) {
		$deletes[] = intval($member['id']);
	}

	//データを編集
	$resource = db_update(array(
		'update' => DATABASE_PREFIX . 'members',
		'set'    => array(
			'deleted' => localdate('Y-m-d H:i:s')
		),
		'where'  => isset($queries['where']) ? $queries['where'] : '',
		'limit'  => isset($queries['limit']) ? $queries['limit'] : ''
	));
	if (!$resource) {
		error('データを削除できません。');
	}

	if ($options['file'] == true) {
		//関連するファイルを削除
		foreach ($deletes as $delete) {
			directory_rmdir($GLOBALS['file_targets']['member'] . $delete . '/');
		}
	}

	return $resource;
}

/*
 * 名簿の正規化
 */
function normalize_members($queries, $options = array())
{
	//成績
	if (isset($queries['grade'])) {
		$queries['grade'] = mb_convert_kana($queries['grade'], 'n', MAIN_INTERNAL_ENCODING);
	}

	//生年月日
	if (isset($queries['birthday'])) {
		if (is_array($queries['birthday'])) {
			$queries['birthday'] = implode('-', array_values(array_filter($queries['birthday'], 'strlen')));
		}
		$queries['birthday'] = mb_convert_kana($queries['birthday'], 'n', MAIN_INTERNAL_ENCODING);
	}

	//電話番号
	if (isset($queries['tel'])) {
		if (is_array($queries['tel'])) {
			$queries['tel'] = implode('-', array_values(array_filter($queries['tel'], 'strlen')));
		}
		$queries['tel'] = mb_convert_kana($queries['tel'], 'n', MAIN_INTERNAL_ENCODING);
	}

	return $queries;
}

/*
 * 名簿の検証
 */
function validate_members($queries, $options = array())
{
	$messages = array();

	//クラス
	if (isset($queries['class_id'])) {
		if ($queries['class_id'] == '') {
			$messages['class_id'] = 'クラスが入力されていません。';
		}
	}

	//名前
	if (isset($queries['name'])) {
		if ($queries['name'] == '') {
			$messages['name'] = '名前が入力されていません。';
		} elseif (mb_strlen($queries['name'], MAIN_INTERNAL_ENCODING) > 20) {
			$messages['name'] = '名前は20文字以内で入力してください。';
		}
	}

	//名前（フリガナ）
	if (isset($queries['name_kana'])) {
		if ($queries['name_kana'] == '') {
			$messages['name_kana'] = '名前（フリガナ）が入力されていません。';
		} elseif (!preg_match('/^[ァ-ヶー]+$/u', $queries['name_kana'])) {
			$messages['name_kana'] = '名前（フリガナ）は全角カタカナで入力してください。';
		} elseif (mb_strlen($queries['name_kana'], MAIN_INTERNAL_ENCODING) > 20) {
			$messages['name_kana'] = '名前（フリガナ）は20文字以内で入力してください。';
		}
	}

	//成績
	if (isset($queries['grade'])) {
		if ($queries['grade'] == '') {
			$messages['grade'] = '成績が入力されていません。';
		} elseif (!preg_match('/^\d+$/', $queries['grade'])) {
			$messages['grade'] = '成績は半角数字で入力してください。';
		} elseif (mb_strlen($queries['grade'], MAIN_INTERNAL_ENCODING) > 3) {
			$messages['grade'] = '成績は3桁以内で入力してください。';
		}
	}

	//生年月日
	if (isset($queries['birthday'])) {
		if ($queries['birthday'] == '') {
		} elseif (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $queries['birthday'], $matches)) {
			$messages['birthday'] = '生年月日の書式が不正です。';
		} elseif (!checkdate($matches[2], $matches[3], $matches[1])) {
			$messages['birthday'] = '生年月日の値が不正です。';
		}
	}

	//メールアドレス
	if (isset($queries['email'])) {
		if ($queries['email'] == '') {
		} elseif (!preg_match('/^[^@\s]+@[^@\s]+$/', $queries['email'])) {
			$messages['email'] = 'メールアドレスの入力内容が正しくありません。';
		} elseif (mb_strlen($queries['email'], MAIN_INTERNAL_ENCODING) > 80) {
			$messages['email'] = 'メールアドレスは80文字以内で入力してください。';
		}
	}

	//電話番号
	if (isset($queries['tel'])) {
		if ($queries['tel'] == '') {
		} elseif (!preg_match('/^\d+-\d+-\d+$/', $queries['tel'])) {
			$messages['tel'] = '電話番号の書式が不正です。';
		} elseif (mb_strlen($queries['tel'], MAIN_INTERNAL_ENCODING) > 20) {
			$messages['tel'] = '電話番号は20文字以内で入力してください。';
		}
	}

	//メモ
	if (isset($queries['memo'])) {
		if ($queries['memo'] == '') {
		} elseif (mb_strlen($queries['memo'], MAIN_INTERNAL_ENCODING) > 1000) {
			$messages['memo'] = 'メモは1000文字以内で入力してください。';
		}
	}

	//公開
	if (isset($queries['public'])) {
		if (!preg_match('/^(0|1)$/', $queries['public'])) {
			$messages['public'] = '公開の書式が不正です。';
		}
	}

	return $messages;
}

/*
 * 名簿の絞り込み
 */
function filter_members($queries, $options = array())
{
	$options = array(
		'associate' => isset($options['associate']) ? $options['associate'] : false
	);

	if ($options['associate'] == true) {
		$wheres = array();
		$pagers = array();

		//教室を取得
		if (isset($queries['class_id'])) {
			if (is_array($queries['class_id'])) {
				$classes = array();
				foreach ($queries['class_id'] as $class_id) {
					$classes[] = 'members.class_id = ' . db_escape($class_id);
					$pagers[]  = 'class_id[]=' . urlencode($class_id);
				}
				$wheres[] = '(' . implode(' OR ', $classes) . ')';
			}
		}

		//名前を取得
		if (isset($queries['name'])) {
			if ($queries['name'] != '') {
				$wheres[] = '(members.name LIKE ' . db_escape('%' . $queries['name'] . '%') . ' OR members.name_kana LIKE ' . db_escape('%' . $queries['name'] . '%') . ')';
				$pagers[] = 'name=' . urlencode($queries['name']);
			}
		}

		//成績を取得
		if (isset($queries['grade'])) {
			if ($queries['grade'] != '') {
				$wheres[] = 'members.grade = ' . db_escape($queries['grade']);
				$pagers[] = 'grade=' . urlencode($queries['grade']);
			}
		}

		//メールアドレスを取得
		if (isset($queries['email'])) {
			if ($queries['email'] != '') {
				$wheres[] = 'members.email LIKE ' . db_escape('%' . $queries['email'] . '%');
				$pagers[] = 'email=' . urlencode($queries['email']);
			}
		}

		$results = array(
			'where' => implode(' AND ', $wheres),
			'pager' => implode('&amp;', $pagers)
		);
	} else {
		$results = array(
			'where' => null,
			'pager' => null
		);
	}

	return $results;
}

/*
 * ファイルの保存
 */
function save_members($id, $files)
{
	foreach (array_keys($files) as $file) {
		if (empty($files[$file]['delete']) && !empty($files[$file]['name'])) {
			if (preg_match('/\.(.*)$/', $files[$file]['name'], $matches)) {
				$directory = $GLOBALS['file_targets']['member'] . intval($id) . '/';
				$filename  = $file . '.' . $matches[1];

				directory_mkdir($directory);

				if (file_put_contents($directory . $filename, $files[$file]['data']) === false) {
					error('ファイル ' . $filename . ' を保存できません。');
				} else {
					$resource = db_update(array(
						'update' => DATABASE_PREFIX . 'members',
						'set'    => array(
							$file => $filename
						),
						'where'  => array(
							'id = :id',
							array(
								'id' => $id
							)
						)
					));
					if (!$resource) {
						error('データを編集できません。');
					}

					file_resize($directory . $filename, $directory . 'thumbnail_' . $filename, $GLOBALS['resize_width'], $GLOBALS['resize_height'], $GLOBALS['resize_quality']);
				}
			} else {
				error('ファイル ' . $files[$file]['name'] . ' の拡張子を取得できません。');
			}
		}
	}
}

/*
 * ファイルの削除
 */
function remove_members($id, $files)
{
	foreach (array_keys($files) as $file) {
		if (!empty($files[$file]['delete']) || !empty($files[$file]['name'])) {
			$members = db_select(array(
				'select' => $file,
				'from'   => DATABASE_PREFIX . 'members',
				'where'  => array(
					'id = :id',
					array(
						'id' => $id
					)
				)
			));
			if (empty($members)) {
				warning('編集データが見つかりません。');
			} else {
				$member = $members[0];
			}

			if (is_file($GLOBALS['file_targets']['member'] . intval($id) . '/' . $member[$file])) {
				unlink($GLOBALS['file_targets']['member'] . intval($id) . '/' . $member[$file]);

				$resource = db_update(array(
					'update' => DATABASE_PREFIX . 'members',
					'set'    => array(
						$file => null
					),
					'where'  => array(
						'id = :id',
						array(
							'id' => $id
						)
					)
				));
				if (!$resource) {
					error('データを編集できません。');
				}
			}
		}
	}
}

/*
 * 名簿のフォーム用データ作成
 */
function form_members($data)
{
	//電話番号
	if (isset($data['tel'])) {
		$data['tel'] = explode('-', $data['tel']);

		if (!isset($data['tel'][0])) {
			$data['tel'][0] = '';
		}
		if (!isset($data['tel'][1])) {
			$data['tel'][1] = '';
		}
		if (!isset($data['tel'][2])) {
			$data['tel'][2] = '';
		}
	}

	return $data;
}

/*
 * 名簿の初期値
 */
function default_members()
{
	return array(
		'id'        => null,
		'created'   => localdate('Y-m-d H:i:s'),
		'modified'  => localdate('Y-m-d H:i:s'),
		'deleted'   => null,
		'class_id'  => 0,
		'name'      => '',
		'name_kana' => '',
		'grade'     => 0,
		'birthday'  => null,
		'email'     => null,
		'tel'       => null,
		'memo'      => null,
		'image_01'  => null,
		'image_02'  => null,
		'public'    => 1
	);
}