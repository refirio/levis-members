<?php

import('libs/plugins/ui.php');
import('libs/plugins/environment.php');

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;
}

// 操作ログを取得
$_view['logs'] = select_logs(array(
    'order_by' => 'logs.id DESC',
    'limit'    => array(
        ':offset, :limit',
        array(
            'offset' => $GLOBALS['config']['limits']['log'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['log'],
        ),
    ),
), array(
    'associate' => true,
));

$_view['log_count'] = select_logs(array(
    'select' => 'COUNT(*) AS count',
), array(
    'associate' => true,
));
$_view['log_count'] = $_view['log_count'][0]['count'];
$_view['log_page']  = ceil($_view['log_count'] / $GLOBALS['config']['limits']['log']);

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $_view['log_count'],
    'size'  => $GLOBALS['config']['limits']['log'],
    'width' => $GLOBALS['config']['pagers']['log'],
    'query' => '?',
));
$_view['log_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];

// タイトル
$_view['title'] = 'ログ確認';
