<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>">
        <title><?php isset($_view['title']) ? h($_view['title'] . ' | ') : '' ?>デモ</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('common.css')) ?>">
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('jquery.subwindow.css')) ?>">
        <?php isset($_view['link']) ? e($_view['link']) : '' ?>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery.subwindow.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('common.js')) ?>"></script>
        <?php isset($_view['script']) ? e($_view['script']) : '' ?>
    </head>
    <body>
        <h1>デモ</h1>
        <?php if (!empty($_SESSION['auth']['user']['id'])) : ?>
        <p>ユーザ <em><?php h($_view['_user']['username']) ?></em> としてログインしています。</p>
        <?php endif ?>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/">教室一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user">ログイン</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin">管理者用</a></li>
        </ul>
