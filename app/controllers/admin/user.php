<?php

import('libs/plugins/ui.php');

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;

    $_SESSION['bulk']['user'] = [];
}

// ユーザを取得
$_view['users'] = model('select_users', [
    'order_by' => 'users.id',
    'limit'    => [
        ':offset, :limit',
        [
            'offset' => $GLOBALS['config']['limits']['user'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['user'],
        ],
    ],
], [
    'associate' => true,
]);

$_view['user_count'] = model('select_users', [
    'select' => 'COUNT(*) AS count',
], [
    'associate' => true,
]);
$_view['user_count'] = $_view['user_count'][0]['count'];
$_view['user_page']  = ceil($_view['user_count'] / $GLOBALS['config']['limits']['user']);

// ページャー
$pager = ui_pager([
    'key'   => 'page',
    'count' => $_view['user_count'],
    'size'  => $GLOBALS['config']['limits']['user'],
    'width' => $GLOBALS['config']['pagers']['user'],
    'query' => '?',
]);
$_view['user_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$_view['title'] = 'ユーザ一覧';
