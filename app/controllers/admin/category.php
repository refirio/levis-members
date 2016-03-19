<?php

//分類を取得
$view['categories'] = select_categories(array(
    'order_by' => 'sort, id',
));

/*
//sortの最大値と最小値を取得
$view['category_sorts'] = select_categories(array(
    'select' => 'MAX(sort) AS max, MIN(sort) AS min',
));
$view['category_sorts'] = $view['category_sorts'][0];
*/

//タイトル
$view['title'] = '分類一覧';
