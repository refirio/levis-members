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
$classes = model('select_classes', [
    'where' => [
        'code = :code',
        [
            'code' => $_GET['code'],
        ],
    ],
]);
if (empty($classes)) {
    warning('教室が見つかりません。');
} else {
    $_view['class'] = $classes[0];
}

// 名簿を取得
$_view['members'] = model('select_members', [
    'where'    => [
        'members.class_id = :class_id AND members.public = 1',
        [
            'class_id' => $_view['class']['id'],
        ],
    ],
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
    'select' => 'COUNT(*) AS count',
    'where'  => [
        'members.class_id = :class_id AND members.public = 1',
        [
            'class_id' => $_view['class']['id'],
        ],
    ],
], [
    'associate' => true,
]);
$_view['member_count'] = $_view['member_count'][0]['count'];
$_view['member_page']  = ceil($_view['member_count'] / $GLOBALS['config']['limits']['member']);

// タイトル
$_view['title'] = $_view['class']['name'];
