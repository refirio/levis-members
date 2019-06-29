<?php

// 分類を取得
$_view['categories'] = select_categories(array(
    'order_by' => 'sort, id',
));

// タイトル
$_view['title'] = '分類一覧';
