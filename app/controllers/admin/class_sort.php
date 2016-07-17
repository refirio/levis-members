<?php

if (isset($_POST['sort'])) {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //トランザクションを開始
    db_transaction();

    //並び順を更新
    service_class_sort($_POST['sort']);

    //トランザクションを終了
    db_commit();

    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        ok();

        exit;
    } else {
        //リダイレクト
        redirect('/admin/class?ok=sort');
    }
} else {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //トランザクションを開始
    db_transaction();

    //移動
    service_class_move($_GET['id'], $_GET['target']);

    //トランザクションを終了
    db_commit();

    //リダイレクト
    redirect('/admin/class?ok=sort');
}
