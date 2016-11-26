<?php

// ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

// 削除対象を保持
if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
    if (!isset($_SESSION['bulk']['member'])) {
        $_SESSION['bulk']['member'] = array();
    }
    if (empty($_POST['id'])) {
        foreach ($_POST['list'] as $id => $checked) {
            if ($checked === '1') {
                $_SESSION['bulk']['member'][$id] = true;
            } else {
                unset($_SESSION['bulk']['member'][$id]);
            }
        }
    } else {
        if ($_POST['checked'] === '1') {
            $_SESSION['bulk']['member'][$_POST['id']] = true;
        } else {
            unset($_SESSION['bulk']['member'][$_POST['id']]);
        }
    }

    ok();
}

if (!empty($_POST['id'])) {
    // トランザクションを開始
    db_transaction();

    // 名簿を削除
    $resource = delete_members(array(
        'where' => array(
            'id = :id',
            array(
                'id' => $_POST['id'],
            ),
        ),
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    // トランザクションを終了
    db_commit();

    // リダイレクト
    redirect('/admin/member?ok=delete');
} elseif (!empty($_SESSION['bulk']['member'])) {
    // トランザクションを開始
    db_transaction();

    // 名簿を削除
    $resource = delete_members(array(
        'where' => 'id IN(' . implode(',', array_map('db_escape', array_keys($_SESSION['bulk']['member']))) . ')',
    ));
    if (!$resource) {
        error('データを削除できません。');
    }

    // トランザクションを終了
    db_commit();

    // リダイレクト
    redirect('/admin/member?page=' . intval($_POST['page']) . '&ok=delete');
} else {
    // リダイレクト
    redirect('/admin/member?warning=delete');
}
