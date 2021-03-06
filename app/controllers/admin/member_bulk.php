<?php

if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
    // 処理対象を保持
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
} elseif (!empty($_SESSION['bulk']['member'])) {
    // 処理対象を取得
    $_view['members'] = select_members(array(
        'where'    => 'members.id IN(' . implode(',', array_map('db_escape', array_keys($_SESSION['bulk']['member']))) . ')',
        'order_by' => 'members.id',
    ), array(
        'associate' => true,
    ));
    $_view['member_bulks'] = array_keys($_SESSION['bulk']['member']);

    // 教室を取得
    $classes = select_classes(array(
        'order_by' => 'sort, id',
    ));
    $class_sets = array();
    foreach ($classes as $class) {
        $class_sets[$class['id']] = $class;
    }
    $_view['class_sets'] = $class_sets;
    $_view['classes']    = $classes;

    // 分類を取得
    $categories = select_categories(array(
        'order_by' => 'sort, id',
    ));
    $category_sets = array();
    foreach ($categories as $category) {
        $category_sets[$category['id']] = $category;
    }
    $_view['category_sets'] = $category_sets;
    $_view['categories']    = $categories;
}

// タイトル
$_view['title'] = '一括処理';
