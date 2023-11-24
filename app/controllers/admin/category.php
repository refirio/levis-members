<?php

// 分類を取得
$_view['categories'] = model('select_categories', [
    'order_by' => 'sort, id',
]);

// タイトル
$_view['title'] = '分類一覧';
