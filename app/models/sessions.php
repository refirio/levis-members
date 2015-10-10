<?php

/*
 * セッションの登録
 */
function insert_sessions($queries, $options = array())
{
	$queries = db_placeholder($queries);

	//初期値を取得
	$defaults = default_sessions();

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
	$queries['insert_into'] = DATABASE_PREFIX . 'sessions';

	$resource = db_insert($queries);

	return $resource;
}

/*
 * セッションの編集
 */
function update_sessions($queries, $options = array())
{
	$queries = db_placeholder($queries);

	//初期値を取得
	$defaults = default_sessions();

	if (isset($queries['set']['modified'])) {
		if ($queries['set']['modified'] === false) {
			unset($queries['set']['modified']);
		}
	} else {
		$queries['set']['modified'] = $defaults['modified'];
	}

	//データを編集
	$queries['update'] = DATABASE_PREFIX . 'sessions';

	$resource = db_update($queries);

	return $resource;
}

/*
 * セッションの初期値
 */
function default_sessions()
{
	return array(
		'id'       => null,
		'created'  => localdate('Y-m-d H:i:s'),
		'modified' => localdate('Y-m-d H:i:s'),
		'user_id'  => 0,
		'agent'    => null,
		'keep'     => 0,
		'twostep'  => 0,
		'expire'   => localdate('Y-m-d H:i:s')
	);
}
