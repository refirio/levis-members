<?php

//ログアウト
$resource = update_sessions(array(
    'set'   => array(
        'keep' => 0
    ),
    'where' => array(
        'id = :session AND user_id = :user_id',
        array(
            'session' => $_COOKIE['session'],
            'user_id' => $_SESSION['user']['id'],
        ),
    ),
));
if (!$resource) {
    error('データを編集できません。');
}

unset($_SESSION['user']);

//リダイレクト
redirect('/user');
