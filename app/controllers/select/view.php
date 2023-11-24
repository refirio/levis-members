<?php

if (empty($_GET['id'])) {
    // 名簿を取得
    $members = model('select_members', [
        'select'   => 'DISTINCT members.id, members.name',
        'where'    => [
            'members.class_id = :class_id AND members.public = 1',
            [
                'class_id' => $_GET['class_id'],
            ],
        ],
        'order_by' => 'members.id',
    ], [
        'associate' => true,
    ]);

    header('Content-Type: application/json; charset=' . MAIN_CHARSET);

    echo json_encode([
        'status'  => 'OK',
        'members' => $members,
    ]);

    exit;
} else {
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
}

// タイトル
$_view['title'] = '名簿表示';
