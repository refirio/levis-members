<?php

// 教室を取得
$_view['classes'] = service_class_select(array(
    'order_by' => 'sort, id',
));

// タイトル
$_view['title'] = '名簿選択';
