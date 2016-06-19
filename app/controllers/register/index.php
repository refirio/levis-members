<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'user' => normalize_users(array(
            'id'    => null,
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
        )),
    );

    //入力データを検証＆登録
    $warnings = validate_users($post['user'], array('duplicate' => false));
    if (isset($_POST['type']) && $_POST['type'] === 'json') {
        if (empty($warnings)) {
            ok();
        } else {
            warning($warnings);
        }
    } else {
        if (empty($warnings)) {
            //メールアドレスの存在を確認
            $users = select_users(array(
                'where' => array(
                    'email = :email',
                    array(
                        'email' => $post['user']['email'],
                    ),
                ),
            ));
            if (empty($users)) {
                $exist = null;
            } else {
                if ($users[0]['regular']) {
                    error('指定されたメールアドレスはすでに登録されています。');
                } else {
                    $exist = true;
                }
            }

            //トークンを作成
            $token        = rand_string();
            $token_code   = rand_number(1000, 9999);
            $token_expire = localdate('Y-m-d H:i:s', time() + 60 * 60 * 24);

            //トランザクションを開始
            db_transaction();

            if ($exist) {
                //仮ユーザ情報を更新
                $resource = update_users(array(
                    'set'   => array(
                        'token'        => $token,
                        'token_code'   => $token_code,
                        'token_expire' => $token_expire,
                    ),
                    'where' => array(
                        'email = :email AND regular = 0',
                        array(
                            'email' => $post['user']['email'],
                        ),
                    ),
                ));
                if (!$resource) {
                    error('指定されたメールアドレスが見つかりません。');
                }
            } else {
                //仮ユーザ情報を登録
                $resource = insert_users(array(
                    'values' => array(
                        'username'     => md5($post['user']['email']),
                        'email'        => $post['user']['email'],
                        'token'        => $token,
                        'token_code'   => $token_code,
                        'token_expire' => $token_expire,
                    )
                ));
                if (!$resource) {
                    error('データを登録できません。');
                }
            }

            //仮ユーザ情報を取得
            $users = select_users(array(
                'where' => array(
                    'email = :email AND regular = 0',
                    array(
                        'email' => $post['user']['email'],
                    ),
                ),
            ));

            //メール送信内容を作成
            $view['url'] = $GLOBALS['config']['http_url'] . MAIN_FILE . '/register/form?key=' . urlencode($users[0]['email']) . '&token=' . $users[0]['token'];

            $_SESSION['token_code'] = $users[0]['token_code'];

            $to      = $users[0]['email'];
            $subject = $GLOBALS['config']['mail_subjects']['register/send'];
            $message = view('mail/register/send.php', true);
            $headers = $GLOBALS['config']['mail_headers'];

            //メールを送信
            if (service_mail_send($to, $subject, $message, $headers) === false) {
                error('メールを送信できません。');
            }

            //トランザクションを終了
            db_commit();

            //リダイレクト
            redirect('/register/send');
        } else {
            $view['user'] = $post['user'];

            $view['warnings'] = $warnings;
        }
    }
} else {
    $view['user'] = array(
        'email' => '',
    );
}

//タイトル
$view['title'] = 'ユーザ登録';
