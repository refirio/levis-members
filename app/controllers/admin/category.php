<?php

// 分類を取得
$_view['categories'] = service_category_select(array(
    'order_by' => 'sort, id',
));

// タイトル
$_view['title'] = '分類一覧';
