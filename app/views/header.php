<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>" />
        <title><?php isset($view['title']) ? h($view['title'] . ' | ') : '' ?>サンプル</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/common.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/jquery.subwindow.css" />
        <?php isset($view['link']) ? e($view['link']) : '' ?>
        <script src="<?php t($GLOBALS['http_path']) ?>js/jquery.js"></script>
        <script src="<?php t($GLOBALS['http_path']) ?>js/jquery.subwindow.js"></script>
        <script src="<?php t($GLOBALS['http_path']) ?>js/common.js"></script>
        <?php isset($view['script']) ? e($view['script']) : '' ?>
    </head>
    <body>
        <h1>サンプル</h1>
        <?php if (!empty($_SESSION['user']['id'])) : ?>
        <p>ユーザ <em><?php h($view['_user']['username']) ?></em> としてログインしています。</p>
        <?php endif ?>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>">教室一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user">ログイン</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin">管理者用</a></li>
        </ul>
