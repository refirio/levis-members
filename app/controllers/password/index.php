<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    // メールアドレスを検証
    $users = select_users(array(
        'where' => array(
            'email = :email AND regular = 1',
            array(
                'email' => $_POST['email'],
            ),
        ),
    ));
    if (empty($users)) {
        $warnings = array('email' => '指定されたメールアドレスが見つかりません。');
    } else {
        $warnings = array();
    }

    // 入力データを検証＆登録
    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            // トランザクションを開始
            db_transaction();

            // パスワード再発行用URLを通知
            $resource = update_users(array(
                'set'   => array(
                    'token'        => rand_string(),
                    'token_code'   => rand_number(1000, 9999),
                    'token_expire' => localdate('Y-m-d H:i:s', time() + 60 * 60 * 24),
                ),
                'where' => array(
                    'email = :email AND regular = 1',
                    array(
                        'email' => $_POST['email'],
                    ),
                ),
            ));
            if (!$resource) {
                error('指定されたメールアドレスが見つかりません。');
            }

            $users = select_users(array(
                'where' => array(
                    'email = :email AND regular = 1',
                    array(
                        'email' => $_POST['email'],
                    ),
                ),
            ));

            // メール送信内容を作成
            $view['url'] = $GLOBALS['config']['http_url'] . MAIN_FILE . '/password/form?key=' . urlencode($users[0]['email']) . '&token=' . $users[0]['token'];

            $_SESSION['expect']['token_code'] = $users[0]['token_code'];

            $to      = $users[0]['email'];
            $subject = $GLOBALS['config']['mail_subjects']['password/send'];
            $message = view('mail/password/send.php', true);
            $headers = $GLOBALS['config']['mail_headers'];

            // メールを送信
            if (service_mail_send($to, $subject, $message, $headers) === false) {
                error('メールを送信できません。');
            }

            // トランザクションを終了
            db_commit();

            // リダイレクト
            redirect('/password/send');
        } else {
            $view['user'] = $_POST;

            $view['warnings'] = $warnings;
        }
    }
} else {
    $view['user'] = array(
        'email' => '',
    );
}

// タイトル
$view['title'] = 'パスワード再発行';
