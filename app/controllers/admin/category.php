<?php

// 分類を取得
$view['categories'] = select_categories(array(
    'order_by' => 'sort, id',
));

// タイトル
$view['title'] = '分類一覧';
