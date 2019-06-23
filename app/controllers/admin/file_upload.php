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

    // 入力データを検証＆登録
    if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
        if (count($_FILES['files']['tmp_name']) > 1) {
            error('アップロードできるファイルは1つです。', array('token' => token('create', $_GET['view'])));
        } else {
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
            $_FILES['file']['name']     = $_FILES['files']['name'][0];
        }
    }

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $names = array();
        $ext   = null;
        if (empty($GLOBALS['config']['file_permissions'][$_GET['format']])) {
            $ext = '*';
        } else {
            foreach (array_keys($GLOBALS['config']['file_permissions'][$_GET['format']]) as $permission) {
                $names[] = $GLOBALS['config']['file_permissions'][$_GET['format']][$permission]['name'];

                if (preg_match($GLOBALS['config']['file_permissions'][$_GET['format']][$permission]['regexp'], $_FILES['file']['name'])) {
                    $ext = $GLOBALS['config']['file_permissions'][$_GET['format']][$permission]['ext'];

                    break;
                }
            }
        }

        if ($ext === null) {
            $_view['warnings'] = array('アップロードできるファイル形式は' . implode('、', $names) . 'のみです。');
        } else {
            $_SESSION['file'][$_GET['target']][$_GET['key']] = array(
                'name' => $_FILES['file']['name'],
                'data' => file_get_contents($_FILES['file']['tmp_name']),
            );

            if (isset($_FILES['files'])) {
                ok(null, array('token' => token('create', $_GET['view'])));
            } else {
                // リダイレクト
                redirect('/admin/file_upload?ok=post&view=' . $_GET['view'] . '&target=' . $_GET['target'] . '&key=' . $_GET['key'] . '&format=' . $_GET['format'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
            }
        }
    } else {
        $_view['warnings'] = array('ファイルを選択してください。');
    }
}

// 初期データを取得
if (empty($_view['warnings'])) {
    if (isset($_SESSION['file'][$_GET['target']][$_GET['key']]['data'])) {
        $file = true;
    } elseif (isset($_GET['id'])) {
        $results = array();
        if ($_GET['target'] === 'class') {
            $results = service_class_select(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id'],
                    ),
                ),
            ));
        } elseif ($_GET['target'] === 'member') {
            $results = service_member_select(array(
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

        $file = $result[$_GET['key']] ? true : false;
    } else {
        $file = false;
    }

    if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
        ok(null, array('token' => token('create', $_GET['view'])));
    }
} else{
    if (isset($_POST['_type']) && $_POST['_type'] === 'json') {
        error($_view['warnings'][0], array('token' => token('create', $_GET['view'])));
    }
}

$_view['target'] = $_GET['target'];
$_view['key']    = $_GET['key'];
$_view['format'] = $_GET['format'];
$_view['file']   = $file;
