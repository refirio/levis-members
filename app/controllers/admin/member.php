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

    $_SESSION['bulk']['member'] = array();
}

// 名簿を取得
if (empty($_GET['class_id'])) {
    $where = null;
} else {
    $where = array(
        'members.class_id = :class_id',
        array(
            'class_id' => $_GET['class_id'],
        ),
    );
}
$_view['members'] = service_member_select(array(
    'where'    => $where,
    'order_by' => 'id',
    'limit'    => array(
        ':offset, :limit',
        array(
            'offset' => $GLOBALS['config']['limits']['member'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['member'],
        ),
    ),
), array(
    'associate' => true,
));

$_view['member_count'] = service_member_select(array(
    'select' => 'COUNT(DISTINCT members.id) AS count',
    'where'  => $where,
), array(
    'associate' => true,
));
$_view['member_count'] = $_view['member_count'][0]['count'];
$_view['member_page']  = ceil($_view['member_count'] / $GLOBALS['config']['limits']['member']);

// 教室を取得
$classes = service_class_select(array(
    'order_by' => 'sort, id',
));
$class_sets = array();
foreach ($classes as $class) {
    $class_sets[$class['id']] = $class;
}
$_view['class_sets'] = $class_sets;
$_view['classes']    = $classes;

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $_view['member_count'],
    'size'  => $GLOBALS['config']['limits']['member'],
    'width' => $GLOBALS['config']['pagers']['member'],
    'query' => '?class_id=' . $_GET['class_id'] . '&amp;',
));
$_view['member_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$_view['title'] = '名簿一覧';
