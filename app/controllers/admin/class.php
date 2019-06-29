<?php

// 教室を取得
$_view['classes'] = select_classes(array(
    'order_by' => 'sort, id',
));

// タイトル
$_view['title'] = '教室一覧';
