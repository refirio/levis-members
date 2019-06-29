<?php

// コードを取得
if (isset($_params[1])) {
    $_GET['code'] = $_params[1];
}
if (!isset($_GET['code']) || !preg_match('/^[\w\-]+$/', $_GET['code'])) {
    error('不正なアクセスです。');
}

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;
}

// 教室を取得
$classes = select_classes(array(
    'where' => array(
        'code = :code',
        array(
            'code' => $_GET['code'],
        ),
    ),
));
if (empty($classes)) {
    warning('教室が見つかりません。');
} else {
    $_view['class'] = $classes[0];
}

// 名簿を取得
$_view['members'] = select_members(array(
    'where'    => array(
        'members.class_id = :class_id AND members.public = 1',
        array(
            'class_id' => $_view['class']['id'],
        ),
    ),
    'order_by' => 'members.id',
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

$_view['member_count'] = select_members(array(
    'select' => 'COUNT(*) AS count',
    'where'  => array(
        'members.class_id = :class_id AND members.public = 1',
        array(
            'class_id' => $_view['class']['id'],
        ),
    ),
), array(
    'associate' => true,
));
$_view['member_count'] = $_view['member_count'][0]['count'];
$_view['member_page']  = ceil($_view['member_count'] / $GLOBALS['config']['limits']['member']);

// タイトル
$_view['title'] = $_view['class']['name'];
