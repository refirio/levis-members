<?php

import('libs/plugins/ui.php');

// 店舗の絞り込み
$filters = filter_members($_GET, [
    'associate' => true,
]);

if ($filters['where'] !== '') {
    $filters['where'] .= ' AND ';
}
$filters['where'] .= 'members.public = 1';

// 検索用文字列を初期化
if (!isset($_GET['class_id'])) {
    $_GET['class_id'] = [];
}
if (!isset($_GET['category_sets'])) {
    $_GET['category_sets'] = [];
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

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;
}

// 名簿を取得
$_view['members'] = model('select_members', [
    'where'    => $filters['where'] ? $filters['where'] : null,
    'order_by' => 'members.id',
    'limit'    => [
        ':offset, :limit',
        [
            'offset' => $GLOBALS['config']['limits']['member'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['member'],
        ],
    ],
], [
    'associate' => true,
]);

$_view['member_count'] = model('select_members', [
    'select' => 'COUNT(DISTINCT members.id) AS count',
    'where'  => $filters['where'] ? $filters['where'] : null,
], [
    'associate' => true,
]);
$_view['member_count'] = $_view['member_count'][0]['count'];
$_view['member_page']  = ceil($_view['member_count'] / $GLOBALS['config']['limits']['member']);

// ページャー
$pager = ui_pager([
    'key'   => 'page',
    'count' => $_view['member_count'],
    'size'  => $GLOBALS['config']['limits']['member'],
    'width' => $GLOBALS['config']['pagers']['member'],
    'query' => '?' . $filters['pager'] . '&amp;',
]);
$_view['member_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// 教室を取得
$_view['classes'] = model('select_classes', [
    'order_by' => 'sort, id',
]);

// 分類を取得
$_view['categories'] = model('select_categories', [
    'order_by' => 'sort, id',
]);

// タイトル
$_view['title'] = '名簿検索';
