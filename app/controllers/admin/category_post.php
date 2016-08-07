<?php

//フォワードを確認
if (forward() === null) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/admin/category_form');
}

//トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['category']['id'])) {
    //分類を登録
    $resource = insert_categories(array(
        'values' => array(
            'name' => $_SESSION['post']['category']['name'],
            'sort' => $_SESSION['post']['category']['sort'],
        ),
    ));
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    //分類を編集
    $resource = update_categories(array(
        'set'   => array(
            'name' => $_SESSION['post']['category']['name'],
        ),
        'where' => array(
            'id = :id',
            array(
                'id' => $_SESSION['post']['category']['id'],
            ),
        ),
    ), array(
        'id'     => intval($_SESSION['post']['category']['id']),
        'update' => $_SESSION['update']['category'],
    ));
    if (!$resource) {
        error('データを編集できません。');
    }
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['update']);

//リダイレクト
redirect('/admin/category?ok=post');
