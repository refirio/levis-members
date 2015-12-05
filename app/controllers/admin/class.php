<?php

//教室を取得
$view['classes'] = select_classes(array(
	'order_by' => 'sort, id'
));

/*
//sortの最大値と最小値を取得
$view['class_sorts'] = select_classes(array(
	'select' => 'MAX(sort) AS max, MIN(sort) AS min'
));
$view['class_sorts'] = $view['class_sorts'][0];
*/
