<?php

if (isset($_POST['sort'])) {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //トランザクションを開始
    db_transaction();

    //並び順を更新
    service_category_sort($_POST['sort']);

    //トランザクションを終了
    db_commit();

    if (isset($_POST['type']) && $_POST['type'] == 'json') {
        header('Content-Type: application/json; charset=' . MAIN_CHARSET);
        echo json_encode(array('status' => 'OK'));
        exit;
    } else {
        //リダイレクト
        redirect('/admin/category?ok=sort');
    }
} else {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //トランザクションを開始
    db_transaction();

    //移動
    service_category_move($_GET['id'], $_GET['target']);

    //トランザクションを終了
    db_commit();

    //リダイレクト
    redirect('/admin/category?ok=sort');
}
