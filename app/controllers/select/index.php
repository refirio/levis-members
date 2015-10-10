<?php

//セッション情報を取得
import('app/controllers/session.php');

//ユーザ情報を取得
import('app/controllers/user.php');

//教室を取得
$view['classes'] = select_classes(array(
	'order_by' => 'sort, id'
));
