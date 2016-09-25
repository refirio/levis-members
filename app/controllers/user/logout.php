<?php

// ログアウト
$resource = update_sessions(array(
    'set'   => array(
        'keep' => 0
    ),
    'where' => array(
        'id = :session AND user_id = :user_id',
        array(
            'session' => $_COOKIE['auth']['session'],
            'user_id' => $_SESSION['auth']['user']['id'],
        ),
    ),
));
if (!$resource) {
    error('データを編集できません。');
}

unset($_SESSION['auth']['user']);

// リファラ
if (isset($_GET['referer'])) {
    $referer .= '?referer=' . urlencode($_GET['referer']);
} else {
    $referer = '';
}

// リダイレクト
redirect('/user' . $referer);
