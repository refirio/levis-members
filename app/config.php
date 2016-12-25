<?php

/*******************************************************************************

 設定ファイル

*******************************************************************************/

/* 公開URL */
$GLOBALS['config']['http_url']  = '';

/* 設置ディレクトリ */
$GLOBALS['config']['http_path'] = dirname($_SERVER['SCRIPT_NAME']) . '/';

/* ログイン情報 */
$GLOBALS['config']['administrators'] = array(
    'admin' => array(
        'password' => '1234',
        'address'  => array(),
    ),
);

/* ハッシュ作成用ソルト */
$GLOBALS['config']['hash_salt'] = 'RKH7X92N4P';

/* 表示件数 */
$GLOBALS['config']['limits'] = array(
    'user'   => 10,
    'member' => 10,
);

/* ページャーの幅 */
$GLOBALS['config']['pagers'] = array(
    'user'   => 5,
    'member' => 5,
);

/* オプション項目 */
$GLOBALS['config']['options'] = array(
    'user' => array(
        // 2段階認証
        'twosteps' => array(
            0 => '設定しない',
            1 => '設定する',
        ),
    ),
    'member' => array(
        // 成績
        'grades' => array(
            0 => '☆☆☆☆☆',
            1 => '★☆☆☆☆',
            2 => '★★☆☆☆',
            3 => '★★★☆☆',
            4 => '★★★★☆',
            5 => '★★★★★',
        ),
        // 公開
        'publics' => array(
            0 => '非公開',
            1 => '公開',
        ),
    ),
);

/* ファイルアップロード先 */
$GLOBALS['config']['file_targets'] = array(
    'class'  => 'files/classes/',
    'member' => 'files/members/',
);

/* ファイルアップロード許可 */
$GLOBALS['config']['file_permissions'] = array(
    'file'  => array(
    ),
    'image' => array(
        'png' => array(
            'name'   => 'PNG',
            'ext'    => 'png',
            'regexp' => '/\.png$/i',
            'mime'   => 'image/png',
        ),
        'jpeg' => array(
            'name'   => 'JPEG',
            'ext'    => 'jpg',
            'regexp' => '/\.(jpeg|jpg|jpe)$/i',
            'mime'   => 'image/jpeg',
        ),
        'gif' => array(
            'name'   => 'GIF',
            'ext'    => 'gif',
            'regexp' => '/\.gif$/i',
            'mime'   => 'image/gif',
        ),
    ),
);

/* 代替ファイル */
$GLOBALS['config']['file_alternatives'] = array(
    'file'  => 'images/file.png',
    'image' => null,
);

/* ダミー画像ファイル */
$GLOBALS['config']['file_dummies'] = array(
    'file'  => 'images/no_file.png',
    'image' => 'images/no_file.png',
);

/* 画像リサイズ時のサイズ */
$GLOBALS['config']['resize_width']  = 100;
$GLOBALS['config']['resize_height'] = 80;

/* 画像リサイズ時のJpeg画質 */
$GLOBALS['config']['resize_quality'] = 85;

/* ログインの有効期限 */
$GLOBALS['config']['login_expire'] = 60 * 60;

/* Cookieの有効期限 */
$GLOBALS['config']['cookie_expire'] = 60 * 60;

/* 2段階認証用メールドメイン */
$GLOBALS['config']['carriers'] = array(
    'disney.ne.jp',
    'docomo.ne.jp',
    'docomo.blackberry.com',
    'emnet.ne.jp',
    'emobile-s.ne.jp',
    'emobile.ne.jp',
    'ezweb.ne.jp',
    'softbank.ne.jp',
    'i.softbank.jp',
    't.vodafone.ne.jp',
    'd.vodafone.ne.jp',
    'h.vodafone.ne.jp',
    'c.vodafone.ne.jp',
    'k.vodafone.ne.jp',
    'r.vodafone.ne.jp',
    'n.vodafone.ne.jp',
    's.vodafone.ne.jp',
    'q.vodafone.ne.jp',
    'willcom.com',
    'pdx.ne.jp',
    'wm.pdx.ne.jp',
    'dk.pdx.ne.jp',
    'di.pdx.ne.jp',
    'dj.pdx.ne.jp',
    'y-mobile.ne.jp',
    'ymobile1.ne.jp',
    'ymobile.ne.jp',
    'yahoo.ne.jp',
    'wcm.ne.jp',
);

/* メールの件名 */
$GLOBALS['config']['mail_subjects'] = array(
    'user/twostep'  => '2段階認証用コード',
    'user/activate' => 'メールアドレス存在確認',
    'password/send' => 'パスワード再発行',
);

/* メールの署名 */
$GLOBALS['config']['mail_signature'] = '
- - - - - - - - - - - - - - - - - - - - 
levis-demo: demo@example.com
- - - - - - - - - - - - - - - - - - - - 
';

/* メールのヘッダ */
$GLOBALS['config']['mail_headers'] = array(
    'X-Mailer' => 'levis-demo',
    'From'     => '"From" <auto@example.com>',
);

/* メールの送信 */
$GLOBALS['config']['mail_send'] = true;
