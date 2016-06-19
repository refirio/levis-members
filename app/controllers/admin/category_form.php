<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'category' => normalize_categories(array(
            'id'   => isset($_POST['id'])   ? $_POST['id']   : '',
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
        ))
    );

    //入力データを検証＆登録
    $warnings = validate_categories($post['category']);
    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            $_SESSION['post']['category'] = $post['category'];

            //リダイレクト
            redirect('/admin/category_post?token=' . token('create'));
        } else {
            $view['category'] = $post['category'];

            $view['warnings'] = $warnings;
        }
    }
} else {
    //初期データを取得
    if (empty($_GET['id'])) {
        $view['category'] = default_categories();
    } else {
        $categories = select_categories(array(
            'where' => array(
                'id = :id',
                array(
                    'id' => $_GET['id'],
                ),
            ),
        ));
        if (empty($categories)) {
            warning('編集データが見つかりません。');
        } else {
            $view['category'] = $categories[0];
        }
    }

    //投稿セッションを初期化
    unset($_SESSION['post']);

    //編集開始日時を記録
    if (!empty($_GET['id'])) {
        $_SESSION['update']['category'] = localdate('Y-m-d H:i:s');
    }
}

//タイトル
if (empty($_GET['id'])) {
    $view['title'] = '分類登録';
} else {
    $view['title'] = '分類編集';
}
