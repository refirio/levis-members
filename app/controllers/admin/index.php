<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ログイン
    foreach ($GLOBALS['config']['administrators'] as $username => $information) {
        if ($_POST['username'] === $username && $_POST['password'] === $information['password']) {
            if (empty($information['address']) || in_array(clientip(), $information['address'])) {
                $_SESSION['auth']['administrator'] = array(
                    'id'   => $_POST['username'],
                    'time' => localdate(),
                );

                break;
            }
        }
    }

    if (empty($_SESSION['auth']['administrator']['id'])) {
        $view['administrator'] = $_POST;

        $view['warnings'] = array('ユーザ名もしくはパスワードが違います。');
    }
} else {
    $addresses = array();
    foreach ($GLOBALS['config']['administrators'] as $information) {
        if (!empty($information['address'])) {
            $addresses = array_merge($addresses, $information['address']);
        }
    }
    if (!empty($addresses) && !in_array(clientip(), $addresses)) {
        error('不正なアクセスです。');
    }

    $view['administrator'] = array(
        'username' => '',
        'password' => '',
    );
}

// ログイン確認
if (!empty($_SESSION['auth']['administrator']['id'])) {
    if ($_REQUEST['work'] === 'index') {
        if (isset($_GET['referer']) && regexp_match('^\/', $_GET['referer'])) {
            $url = $_GET['referer'];
        } else {
            $url = '/admin/home';
        }

        // リダイレクト
        redirect($url);
    } else {
        error('不正なアクセスです。');
    }
}

// タイトル
$view['title'] = '管理者用';
