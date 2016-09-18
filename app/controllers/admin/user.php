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
$view['users'] = select_users(array(
    'where'    => 'users.regular = 1',
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

$view['user_count'] = select_users(array(
    'select' => 'COUNT(*) AS count',
    'where'  => 'users.regular = 1',
), array(
    'associate' => true,
));
$view['user_count'] = $view['user_count'][0]['count'];
$view['user_page']  = ceil($view['user_count'] / $GLOBALS['config']['limits']['user']);

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $view['user_count'],
    'size'  => $GLOBALS['config']['limits']['user'],
    'width' => $GLOBALS['config']['pagers']['user'],
    'query' => '?',
));
$view['user_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$view['title'] = 'ユーザ一覧';
