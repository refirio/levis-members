<?php

/*********************************************************************

 Functions for Class

*********************************************************************/

function class_sort($data)
{
	//トランザクションを開始
	db_transaction();

	//並び順を更新
	foreach ($data as $id => $sort) {
		if (!preg_match('/^[\w\-\/]+$/', $id)) {
			continue;
		}
		if (!preg_match('/^\d+$/', $sort)) {
			continue;
		}

		$resource = update_classes(array(
			'set'   => array(
				'sort' => $sort
			),
			'where' => array(
				'id = :id',
				array(
					'id' => $id
				)
			)
		));
		if (!$resource) {
			return array(0, 'データを編集できません。');
		}
	}

	//トランザクションを終了
	db_commit();

	return array(1, null);
}

function class_move($id, $target)
{
	//トランザクションを開始
	db_transaction();

	//移動元のidとsortを取得
	$class_from = select_classes(array(
		'select' => 'id, sort',
		'where'  => array(
			'id = :id',
			array(
				'id' => $id
			)
		)
	));
	$class_from = $class_from[0];

	//移動先のidとsortを取得
	if ($target == 'up') {
		$class_to = select_classes(array(
			'select'   => 'id, sort',
			'where'    => array(
				'sort < :sort',
				array(
					'sort' => $class_from['sort']
				)
			),
			'order_by' => 'sort DESC',
			'limit'    => 1
		));
		$class_to = $class_to[0];
	} else {
		$class_to = select_classes(array(
			'select'   => 'id, sort',
			'where'    => array(
				'sort > :sort',
				array(
					'sort' => $class_from['sort']
				)
			),
			'order_by' => 'sort',
			'limit'    => 1
		));
		$class_to = $class_to[0];
	}

	if (empty($class_to)) {
		return array(0, '移動元データを取得できません。');
	}

	//移動元と移動先のidとsortを入れ替え
	$resource = update_classes(array(
		'set'   => array(
			'sort' => $class_to['sort']
		),
		'where' => array(
			'id = :id',
			array(
				'id' => $class_from['id']
			)
		)
	));
	if (!$resource) {
		return array(0, '移動元データを編集できません。');
	}

	$resource = update_classes(array(
		'set'   => array(
			'sort' => $class_from['sort']
		),
		'where' => array(
			'id = :id',
			array(
				'id' => $class_to['id']
			)
		)
	));
	if (!$resource) {
		return array(0, '移動先データを編集できません。');
	}

	//トランザクションを終了
	db_commit();

	return array(1, null);
}
