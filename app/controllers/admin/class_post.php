<?php

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/admin/class_form');
}

//トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['class']['id'])) {
    //教室を登録
    $resource = insert_classes(array(
        'values' => array(
            'code' => $_SESSION['post']['class']['code'],
            'name' => $_SESSION['post']['class']['name'],
            'memo' => $_SESSION['post']['class']['memo'],
            'sort' => $_SESSION['post']['class']['sort']
        )
    ), array(
        'files' => array(
            'image_01' => isset($_SESSION['file']['class']['image_01']) ? $_SESSION['file']['class']['image_01'] : array(),
            'image_02' => isset($_SESSION['file']['class']['image_02']) ? $_SESSION['file']['class']['image_02'] : array(),
            'document' => isset($_SESSION['file']['class']['document']) ? $_SESSION['file']['class']['document'] : array()
        )
    ));
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    //教室を編集
    $resource = update_classes(array(
        'set'   => array(
            'code' => $_SESSION['post']['class']['code'],
            'name' => $_SESSION['post']['class']['name'],
            'memo' => $_SESSION['post']['class']['memo']
        ),
        'where' => array(
            'id = :id',
            array(
                'id' => $_SESSION['post']['class']['id']
            )
        )
    ), array(
        'id'     => intval($_SESSION['post']['class']['id']),
        'update' => $_SESSION['update'],
        'files'  => array(
            'image_01' => isset($_SESSION['file']['class']['image_01']) ? $_SESSION['file']['class']['image_01'] : array(),
            'image_02' => isset($_SESSION['file']['class']['image_02']) ? $_SESSION['file']['class']['image_02'] : array(),
            'document' => isset($_SESSION['file']['class']['document']) ? $_SESSION['file']['class']['document'] : array()
        )
    ));
    if (!$resource) {
        error('データを編集できません。');
    }
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['file']);
unset($_SESSION['update']);

//リダイレクト
redirect('/admin/class?ok=post');
