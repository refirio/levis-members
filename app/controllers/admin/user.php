<?php

import('libs/plugins/ui.php');

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;

    $_SESSION['bulk']['user'] = array();
}

// ユーザを取得
$_view['users'] = service_user_select(array(
    'order_by' => 'users.id',
    'limit'    => array(
        ':offset, :limit',
        array(
            'offset' => $GLOBALS['config']['limits']['user'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['user'],
        ),
    ),
), array(
    'associate' => true,
));

$_view['user_count'] = service_user_select(array(
    'select' => 'COUNT(*) AS count',
), array(
    'associate' => true,
));
$_view['user_count'] = $_view['user_count'][0]['count'];
$_view['user_page']  = ceil($_view['user_count'] / $GLOBALS['config']['limits']['user']);

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $_view['user_count'],
    'size'  => $GLOBALS['config']['limits']['user'],
    'width' => $GLOBALS['config']['pagers']['user'],
    'query' => '?',
));
$_view['user_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$_view['title'] = 'ユーザ一覧';
