<?php

// 教室を取得
/*
$_view['classes'] = select_classes(array(
    'order_by' => 'sort, id',
));
*/
$_view['classes'] = model('select_classes', [
    'order_by' => 'sort, id',
    //'order_by' => 'id DESC',
]);
//model(null);
