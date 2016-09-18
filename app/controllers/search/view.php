<?php

// 名簿を取得
$members = select_members(array(
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
    $view['member'] = $members[0];
}

// タイトル
$view['title'] = '名簿表示';
