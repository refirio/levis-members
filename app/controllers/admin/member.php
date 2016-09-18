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

$view['members'] = select_members(array(
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

$view['member_count'] = select_members(array(
    'select' => 'COUNT(DISTINCT members.id) AS count',
    'where'  => $where,
), array(
    'associate' => true,
));
$view['member_count'] = $view['member_count'][0]['count'];
$view['member_page']  = ceil($view['member_count'] / $GLOBALS['config']['limits']['member']);

// 教室を取得
$classes = select_classes(array(
    'order_by' => 'sort, id',
));
$class_sets = array();
foreach ($classes as $class) {
    $class_sets[$class['id']] = $class;
}
$view['class_sets'] = $class_sets;
$view['classes']    = $classes;

// 分類を取得
$categories = select_categories(array(
    'order_by' => 'sort, id',
));
$category_sets = array();
foreach ($categories as $category) {
    $category_sets[$category['id']] = $category;
}
$view['category_sets'] = $category_sets;
$view['categories']    = $categories;

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $view['member_count'],
    'size'  => $GLOBALS['config']['limits']['member'],
    'width' => $GLOBALS['config']['pagers']['member'],
    'query' => '?class_id=' . $_GET['class_id'] . '&amp;',
));
$view['member_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$view['title'] = '名簿一覧';
