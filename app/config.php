<?php

/*******************************************************************************

 設定ファイル

*******************************************************************************/

/* 公開URL */
$GLOBALS['http_url']  = 'http://www.example.com';

/* 設置ディレクトリ */
$GLOBALS['http_path'] = dirname($_SERVER['SCRIPT_NAME']) . '/';

/* ログイン情報 */
$GLOBALS['administrators'] = array(
    'admin' => array(
        'password' => '1234',
        'address'  => array(),
    ),
);

/* ハッシュ作成用ソルト */
$GLOBALS['hash_salt'] = 'RKH7X92N4P';

/* 表示件数 */
$GLOBALS['limits'] = array(
    'user'   => 10,
    'member' => 10,
);

/* ページャーの幅 */
$GLOBALS['pagers'] = array(
    'user'   => 5,
    'member' => 5,
);

/* オプション項目 */
$GLOBALS['options'] = array(
    'user' => array(
        //2段階認証
        'twosteps' => array(
            0 => '設定しない',
            1 => '設定する',
        )
    ),
    'member' => array(
        //成績
        'grades' => array(
            0 => '☆☆☆☆☆',
            1 => '★☆☆☆☆',
            2 => '★★☆☆☆',
            3 => '★★★☆☆',
            4 => '★★★★☆',
            5 => '★★★★★',
        ),
        //公開
        'publics' => array(
            0 => '非公開',
            1 => '公開',
        )
    ),
);

/* ファイルアップロード先 */
$GLOBALS['file_targets'] = array(
    'class'  => 'files/classes/',
    'member' => 'files/members/',
);

/* ファイルアップロード許可 */
$GLOBALS['file_permissions'] = array(
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
$GLOBALS['file_alternatives'] = array(
    'file'  => 'images/file.png',
    'image' => null,
);

/* ダミー画像ファイル */
$GLOBALS['file_dummies'] = array(
    'file'  => 'images/no_file.png',
    'image' => 'images/no_file.png',
);

/* 画像リサイズ時のサイズ */
$GLOBALS['resize_width']  = 100;
$GLOBALS['resize_height'] = 80;

/* 画像リサイズ時のJpeg画質 */
$GLOBALS['resize_quality'] = 85;

/* ログインの有効期限 */
$GLOBALS['login_expire'] = 60 * 60;

/* Cookieの有効期限 */
$GLOBALS['cookie_expire'] = 60 * 60;

/* 2段階認証用メールドメイン */
$GLOBALS['carriers'] = array(
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
$GLOBALS['mail_subjects'] = array(
    'register/send' => 'ユーザ登録',
    'user/twostep'  => '2段階認証用コード',
    'password/send' => 'パスワード再発行',
);

/* メールの署名 */
$GLOBALS['mail_signature'] = '
- - - - - - - - - - - - - - - - - - - - 
levis-sample: sample@example.com
- - - - - - - - - - - - - - - - - - - - 
';

/* メールのヘッダ */
$GLOBALS['mail_headers'] = array(
    'X-Mailer' => 'levis-sample',
    'From'     => '"From" <auto@example.com>',
);
