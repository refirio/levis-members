<?php

// 教室を取得
$view['classes'] = select_classes(array(
    'order_by' => 'sort, id',
));

// タイトル
$view['title'] = '教室一覧';
