<?php

import('libs/plugins/ui.php');

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//店舗の絞り込み
$filters = filter_members($_GET, array(
	'associate' => true
));

if ($filters['where'] != '') {
	$filters['where'] .= ' AND ';
}
$filters['where'] .= 'members.public = 1';

//検索用文字列を初期化
if (!isset($_GET['class_id'])) {
	$_GET['class_id'] = array();
}
if (!isset($_GET['name'])) {
	$_GET['name'] = null;
}
if (!isset($_GET['grade'])) {
	$_GET['grade'] = null;
}
if (!isset($_GET['email'])) {
	$_GET['email'] = null;
}

//ページを取得
if (isset($_GET['page'])) {
	$_GET['page'] = intval($_GET['page']);
} else {
	$_GET['page'] = 1;
}

//名簿を取得
$view['members'] = select_members(array(
	'where'    => $filters['where'] ? $filters['where'] : null,
	'order_by' => 'members.id',
	'limit'    => array(
		':offset, :limit',
		array(
			'offset' => $GLOBALS['limits']['member'] * ($_GET['page'] - 1),
			'limit'  => $GLOBALS['limits']['member']
		)
	)
), array(
	'associate' => true
));

$view['member_count'] = select_members(array(
	'select' => 'COUNT(*) AS count',
	'where'    => $filters['where'] ? $filters['where'] : null
), array(
	'associate' => true
));
$view['member_count'] = $view['member_count'][0]['count'];
$view['member_page']  = ceil($view['member_count'] / $GLOBALS['limits']['member']);

//ページャー
$pager = ui_pager(array(
	'key'   => 'page',
	'count' => $view['member_count'],
	'size'  => $GLOBALS['limits']['member'],
	'width' => $GLOBALS['pagers']['member'],
	'query' => '?' . $filters['pager'] . '&amp;'
));
$view['member_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

//教室を取得
$view['classes'] = select_classes(array(
	'order_by' => 'sort, id'
));
