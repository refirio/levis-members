<?php

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//削除対象を保持
if (isset($_POST['type']) && $_POST['type'] == 'json') {
    if (!isset($_SESSION['bulks'])) {
        $_SESSION['bulks'] = array();
    }
    if (empty($_POST['id'])) {
        foreach ($_POST['list'] as $id => $checked) {
            if ($checked == 1) {
                $_SESSION['bulks'][$id] = true;
            } else {
                unset($_SESSION['bulks'][$id]);
            }
        }
    } else {
        if ($_POST['checked'] == 1) {
            $_SESSION['bulks'][$_POST['id']] = true;
        } else {
            unset($_SESSION['bulks'][$_POST['id']]);
        }
    }

    ok();
}

if (!empty($_POST['id'])) {
    //トランザクションを開始
    db_transaction();

    //ユーザを削除
    $resource = delete_users(array(
        'where' => array(
            'id = :id',
            array(
                'id' => $_POST['id']
            )
        )
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    //トランザクションを終了
    db_commit();

    //リダイレクト
    redirect('/admin/user?ok=delete');
} elseif (!empty($_SESSION['bulks'])) {
    //トランザクションを開始
    db_transaction();

    //ユーザを削除
    $resource = delete_users(array(
        'where' => 'id IN(' . implode(',', array_map('db_escape', array_keys($_SESSION['bulks']))) . ')'
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    //トランザクションを終了
    db_commit();

    //リダイレクト
    redirect('/admin/user?page=' . intval($_POST['page']) . '&ok=delete');
} else {
    //リダイレクト
    redirect('/admin/user?warning=delete');
}
