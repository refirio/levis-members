<?php

// 名簿を取得
$members = service_member_select(array(
    'where' => array(
        'members.id = :id AND members.public = 1',
        array(
            'id' => $_GET['id'],
        ),
    ),
), array(
    'associate' => true,
));
if (empty($members)) {
    warning('名簿が見つかりません。');
} else {
    $_view['member'] = $members[0];
}

// タイトル
$_view['title'] = '名簿表示';
