<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>" />
        <title><?php isset($_view['title']) ? h($_view['title'] . ' | ') : '' ?>管理者用 | デモ</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/common.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/admin.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/jquery.subwindow.css" />
        <?php isset($_view['link']) ? e($_view['link']) : '' ?>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery-ui.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.subwindow.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.upload.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/common.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/admin.js"></script>
        <?php isset($_view['script']) ? e($_view['script']) : '' ?>
    </head>
    <body>
        <h1>デモ</h1>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/">教室一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user">ログイン</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin">管理者用</a></li>
        </ul>
        <h2>管理者用</h2>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/home">メニュー</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/logout">ログアウト</a></li>
        </ul>
