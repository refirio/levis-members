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

$mime    = null;
$content = null;

if (empty($_SESSION['file'][$_GET['target']][$_GET['key']]['delete'])) {
    if (isset($_SESSION['file'][$_GET['target']][$_GET['key']]['name']) && isset($_SESSION['file'][$_GET['target']][$_GET['key']]['data'])) {
        //セッションからファイルを取得
        foreach (array_keys($GLOBALS['file_permissions'][$_GET['format']]) as $permission) {
            if (preg_match($GLOBALS['file_permissions'][$_GET['format']][$permission]['regexp'], $_SESSION['file'][$_GET['target']][$_GET['key']]['name'])) {
                //マイムタイプ
                $mime = $GLOBALS['file_permissions'][$_GET['format']][$permission]['mime'];

                break;
            }
        }

        //コンテンツ
        $content = $_SESSION['file'][$_GET['target']][$_GET['key']]['data'];
    } elseif (isset($_GET['id'])) {
        //登録内容からファイルを取得
        $results = array();
        if ($_GET['target'] == 'class') {
            $results = select_classes(array(
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $_GET['id'],
                    ),
                ),
            ));
        } elseif ($_GET['target'] == 'member') {
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
            warning('データが見つかりません。');
        } else {
            $result = $results[0];
        }

        $file = $GLOBALS['file_targets'][$_GET['target']] . intval($_GET['id']) . '/' . $result[$_GET['key']];

        if (is_file($file)) {
            foreach (array_keys($GLOBALS['file_permissions'][$_GET['format']]) as $permission) {
                if (preg_match($GLOBALS['file_permissions'][$_GET['format']][$permission]['regexp'], $result[$_GET['key']])) {
                    //マイムタイプ
                    $mime = $GLOBALS['file_permissions'][$_GET['format']][$permission]['mime'];

                    break;
                }
            }

            //コンテンツ
            $content = file_get_contents($file);
        }
    }
}

if (isset($_GET['type']) && $_GET['type'] == 'json') {
    //ファイル情報を取得
    if ($content == null) {
        $width  = null;
        $height = null;
    } else {
        list($width, $height) = getimagesize('data:application/octet-stream;base64,' . base64_encode($content));
    }

    header('Content-Type: application/json; charset=' . MAIN_CHARSET);

    echo json_encode(array(
        'status' => 'OK',
        'mime'   => $mime,
        'width'  => $width,
        'height' => $height,
    ));
} else {
    //ファイルを取得
    if ($mime == null) {
        $mime = 'image/png';
    }
    if ($content == null) {
        $mime    = 'image/png';
        $content = file_get_contents($GLOBALS['file_dummies'][$_GET['format']]);
    } elseif (!empty($GLOBALS['file_alternatives'][$_GET['format']])) {
        $mime    = 'image/png';
        $content = file_get_contents($GLOBALS['file_alternatives'][$_GET['format']]);
    }

    header('Content-type: ' . $mime);

    echo $content;
}

exit;
