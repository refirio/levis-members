<?php

// 表示方法を検証
if (!isset($_GET['view'])) {
    $_GET['view'] = 'default';
}

// 対象を検証
if (!preg_match('/^[\w\-]+$/', $_GET['target'])) {
    error('不正なアクセスです。', array('token' => token('create', $_GET['view'])));
}
if (!preg_match('/^[\w\-]+$/', $_GET['key'])) {
    error('不正なアクセスです。', array('token' => token('create', $_GET['view'])));
}

// 形式を検証
if (!preg_match('/^[\w\-]+$/', $_GET['format'])) {
    error('不正なアクセスです。', array('token' => token('create', $_GET['view'])));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check', $_GET['view'])) {
        error('不正なアクセスです。', array('token' => token('create', $_GET['view'])));
    }

    // コンテンツ
    $content = null;
    if (isset($_SESSION['file'][$_GET['target']][$_GET['key']]['name']) && isset($_SESSION['file'][$_GET['target']][$_GET['key']]['data'])) {
        $content = $_SESSION['file'][$_GET['target']][$_GET['key']]['data'];
    } elseif (isset($_GET['id'])) {
        $results = array();
        if ($_GET['target'] === 'class') {
            $results = select_classes(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id'],
                    ),
                ),
            ));
        } elseif ($_GET['target'] === 'member') {
            $results = select_members(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id'],
                    ),
                ),
            ));
        }
        if (empty($results)) {
            warning('編集データが見つかりません。', array('token' => token('create', $_GET['view'])));
        } else {
            $result = $results[0];
        }

        $file = $GLOBALS['config']['file_targets'][$_GET['target']] . intval($_GET['id']) . '/' . $result[$_GET['key']];

        if (is_file($file)) {
            $content = file_get_contents($file);
        }
    }

    // 選択範囲
    $trimming_left   = intval($_POST['trimming']['left']);
    $trimming_top    = intval($_POST['trimming']['top']);
    $trimming_width  = intval($_POST['trimming']['width']);
    $trimming_height = intval($_POST['trimming']['height']);

    $image = imagecreatetruecolor($trimming_width, $trimming_height);

    // トリミング
    $temporary_file = $GLOBALS['config']['file_targets'][$_GET['target']] . session_id();
    if ($image && imagecopyresampled($image, imagecreatefromstring($content), 0, 0, $trimming_left, $trimming_top, $trimming_width, $trimming_height, $trimming_width, $trimming_height)) {
        imagepng($image, $temporary_file);
    } else {
        warning('編集できません。', array('token' => token('create', $_GET['view'])));
    }

    $_SESSION['file'][$_GET['target']][$_GET['key']] = array(
        'name' => 'process.png',
        'data' => file_get_contents($temporary_file),
    );

    unlink($temporary_file);

    // リダイレクト
    redirect('/admin/file_process?ok=post&view=' . $_GET['view'] . '&target=' . $_GET['target'] . '&key=' . $_GET['key'] . '&format=' . $_GET['format'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
}

// 初期データを取得
$_view['id']     = isset($_GET['id']) ? $_GET['id'] : '';
$_view['target'] = $_GET['target'];
$_view['key']    = $_GET['key'];
$_view['format'] = $_GET['format'];
