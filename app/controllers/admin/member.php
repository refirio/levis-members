<?php

import('libs/plugins/ui.php');

// 教室を取得
if (isset($_GET['class_id'])) {
    $_GET['class_id'] = $_GET['class_id'];
} else {
    $_GET['class_id'] = null;
}

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;

    $_SESSION['bulk']['member'] = [];
}

// 名簿を取得
if (empty($_GET['class_id'])) {
    $where = null;
} else {
    $where = [
        'members.class_id = :class_id',
        [
            'class_id' => $_GET['class_id'],
        ],
    ];
}
$_view['members'] = model('select_members', [
    'where'    => $where,
    'order_by' => 'id',
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
    'where'  => $where,
], [
    'associate' => true,
]);
$_view['member_count'] = $_view['member_count'][0]['count'];
$_view['member_page']  = ceil($_view['member_count'] / $GLOBALS['config']['limits']['member']);

// 教室を取得
$classes = model('select_classes', [
    'order_by' => 'sort, id',
]);
$class_sets = [];
foreach ($classes as $class) {
    $class_sets[$class['id']] = $class;
}
$_view['class_sets'] = $class_sets;
$_view['classes']    = $classes;

// ページャー
$pager = ui_pager([
    'key'   => 'page',
    'count' => $_view['member_count'],
    'size'  => $GLOBALS['config']['limits']['member'],
    'width' => $GLOBALS['config']['pagers']['member'],
    'query' => '?class_id=' . $_GET['class_id'] . '&amp;',
]);
$_view['member_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$_view['title'] = '名簿一覧';
