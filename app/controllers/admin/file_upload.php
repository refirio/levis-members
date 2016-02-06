<?php

//対象を検証
if (!preg_match('/^[\w\-]+$/', $_GET['target'])) {
    error('不正なアクセスです。');
}
if (!preg_match('/^[\w\-]+$/', $_GET['key'])) {
    error('不正なアクセスです。');
}

//形式を検証
if (!preg_match('/^[\w\-]+$/', $_GET['format'])) {
    error('不正なアクセスです。');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを検証＆登録
    if (isset($_POST['type']) && $_POST['type'] == 'json') {
        if (count($_FILES['files']['tmp_name']) > 1) {
            error('アップロードできるファイルは1つです。');
        } else {
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
            $_FILES['file']['name']     = $_FILES['files']['name'][0];
        }
    }

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $names = array();
        $ext   = null;
        if (empty($GLOBALS['file_permissions'][$_GET['format']])) {
            $ext = '*';
        } else {
            foreach (array_keys($GLOBALS['file_permissions'][$_GET['format']]) as $permission) {
                $names[] = $GLOBALS['file_permissions'][$_GET['format']][$permission]['name'];

                if (preg_match($GLOBALS['file_permissions'][$_GET['format']][$permission]['regexp'], $_FILES['file']['name'])) {
                    $ext = $GLOBALS['file_permissions'][$_GET['format']][$permission]['ext'];

                    break;
                }
            }
        }

        if ($ext == null) {
            $view['warnings'] = array('アップロードできるファイル形式は' . implode('、', $names) . 'のみです。');
        } else {
            $_SESSION['file'][$_GET['target']][$_GET['key']] = array(
                'name' => $_FILES['file']['name'],
                'data' => file_get_contents($_FILES['file']['tmp_name'])
            );

            if (isset($_FILES['files'])) {
                ok();
            } else {
                //リダイレクト
                redirect('/admin/file_upload?ok=post&target=' . $_GET['target'] . '&key=' . $_GET['key'] . '&format=' . $_GET['format'] . (isset($_GET['id']) ? '&id=' . intval($_GET['id']): ''));
            }
        }
    } else {
        $view['warnings'] = array('ファイルを選択してください。');
    }
}

//初期データを取得
if (empty($view['warnings'])) {
    if (isset($_SESSION['file'][$_GET['target']][$_GET['key']]['data'])) {
        $file = true;
    } elseif (isset($_GET['id'])) {
        $results = array();
        if ($_GET['target'] == 'class') {
            $results = select_classes(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id']
                    )
                )
            ));
        } elseif ($_GET['target'] == 'member') {
            $results = select_members(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id']
                    )
                )
            ));
        }
        if (empty($results)) {
            warning('編集データが見つかりません。');
        } else {
            $result = $results[0];
        }

        $file = $result[$_GET['key']] ? true : false;
    } else {
        $file = false;
    }

    if (isset($_POST['type']) && $_POST['type'] == 'json') {
        ok();
    }
} else{
    if (isset($_POST['type']) && $_POST['type'] == 'json') {
        error($view['warnings'][0]);
    }
}

$view['target'] = $_GET['target'];
$view['key']    = $_GET['key'];
$view['format'] = $_GET['format'];
$view['file']   = $file;
