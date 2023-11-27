<?php

// 教室を取得
$_view['classes'] = model('select_classes', [
    'order_by' => 'sort, id',
]);
