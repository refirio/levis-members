<?php

// 名簿を取得
$members = model('select_members', [
    'where' => [
        'members.id = :id AND members.public = 1',
        [
            'id' => $_GET['id'],
        ],
    ],
], [
    'associate' => true,
]);
if (empty($members)) {
    warning('名簿が見つかりません。');
} else {
    $_view['member'] = $members[0];
}

// タイトル
$_view['title'] = '名簿表示';
