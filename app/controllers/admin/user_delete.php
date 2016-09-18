<?php

// ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

// 削除対象を保持
if (isset($_POST['type']) && $_POST['type'] === 'json') {
    if (!isset($_SESSION['bulk']['user'])) {
        $_SESSION['bulk']['user'] = array();
    }
    if (empty($_POST['id'])) {
        foreach ($_POST['list'] as $id => $checked) {
            if ($checked === '1') {
                $_SESSION['bulk']['user'][$id] = true;
            } else {
                unset($_SESSION['bulk']['user'][$id]);
            }
        }
    } else {
        if ($_POST['checked'] === '1') {
            $_SESSION['bulk']['user'][$_POST['id']] = true;
        } else {
            unset($_SESSION['bulk']['user'][$_POST['id']]);
        }
    }

    ok();
}

if (!empty($_POST['id'])) {
    // トランザクションを開始
    db_transaction();

    // ユーザを削除
    $resource = delete_users(array(
        'where' => array(
            'id = :id AND regular = 1',
            array(
                'id' => $_POST['id'],
            ),
        ),
    ), array(
        'associate' => true,
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    // トランザクションを終了
    db_commit();

    // リダイレクト
    redirect('/admin/user?ok=delete');
} elseif (!empty($_SESSION['bulk']['user'])) {
    // トランザクションを開始
    db_transaction();

    // ユーザを削除
    $resource = delete_users(array(
        'where' => 'id IN(' . implode(',', array_map('db_escape', array_keys($_SESSION['bulk']['user']))) . ') AND regular = 1',
    ), array(
        'associate' => true,
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    // トランザクションを終了
    db_commit();

    // リダイレクト
    redirect('/admin/user?page=' . intval($_POST['page']) . '&ok=delete');
} else {
    // リダイレクト
    redirect('/admin/user?warning=delete');
}
