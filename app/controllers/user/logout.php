<?php

import('app/services/session.php');

// ログアウト
if (isset($_SESSION['auth']['user']['id'])) {
    $resource = service_session_update([
        'set'   => [
            'keep' => 0
        ],
        'where' => [
            'id = :session AND user_id = :user_id',
            [
                'session' => $_COOKIE['auth']['session'],
                'user_id' => $_SESSION['auth']['user']['id'],
            ],
        ],
    ]);
    if (!$resource) {
        error('データを編集できません。');
    }
}

unset($_SESSION['auth']['user']);

// リファラ
if (isset($_GET['referer'])) {
    $referer = '?referer=' . rawurlencode($_GET['referer']);
} else {
    $referer = '';
}

// リダイレクト
redirect('/user' . $referer);
