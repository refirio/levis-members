<?php

if (empty($_GET['id'])) {
    // 名簿を取得
    $members = select_members(array(
        'select'   => 'DISTINCT members.id, members.name',
        'where'    => array(
            'members.class_id = :class_id AND members.public = 1',
            array(
                'class_id' => $_GET['class_id'],
            ),
        ),
        'order_by' => 'members.id',
    ), array(
        'associate' => true,
    ));

    header('Content-Type: application/json; charset=' . MAIN_CHARSET);

    echo json_encode(array(
        'status'  => 'OK',
        'members' => $members,
    ));

    exit;
} else {
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
}

// タイトル
$view['title'] = '名簿表示';
