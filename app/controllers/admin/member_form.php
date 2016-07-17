<?php

import('libs/plugins/file.php');
import('libs/plugins/ui.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ワンタイムトークン
    if ((empty($_POST['view']) || $_POST['view'] !== 'preview') && !token('check')) {
        error('不正なアクセスです。');
    }

    //入力データを整理
    $post = array(
        'member' => normalize_members(array(
            'id'            => isset($_POST['id'])            ? $_POST['id']            : '',
            'class_id'      => isset($_POST['class_id'])      ? $_POST['class_id']      : '',
            'name'          => isset($_POST['name'])          ? $_POST['name']          : '',
            'name_kana'     => isset($_POST['name_kana'])     ? $_POST['name_kana']     : '',
            'grade'         => isset($_POST['grade'])         ? $_POST['grade']         : '',
            'birthday'      => isset($_POST['birthday'])      ? $_POST['birthday']      : '',
            'email'         => isset($_POST['email'])         ? $_POST['email']         : '',
            'tel'           => isset($_POST['tel'])           ? $_POST['tel']           : '',
            'memo'          => isset($_POST['memo'])          ? $_POST['memo']          : '',
            'public'        => isset($_POST['public'])        ? $_POST['public']        : '',
            'category_sets' => isset($_POST['category_sets']) ? $_POST['category_sets'] : array(),
        ))
    );

    if (isset($_POST['view']) && $_POST['view'] === 'preview') {
        //プレビュー
        $view['member'] = $post['member'];
    } else {
        //入力データを検証＆登録
        $warnings = validate_members($post['member']);
        if (isset($_POST['type']) && $_POST['type'] === 'json') {
            if (empty($warnings)) {
                ok();
            } else {
                warning($warnings);
            }
        } else {
            if (empty($warnings)) {
                $_SESSION['post']['member'] = $post['member'];

                //リダイレクト
                redirect('/admin/member_post?token=' . token('create'));
            } else {
                $view['member'] = $post['member'];

                $view['warnings'] = $warnings;
            }
        }
    }
} else {
    //初期データを取得
    if (empty($_GET['id'])) {
        $view['member'] = default_members();
    } else {
        $members = select_members(array(
            'where' => array(
                'members.id = :id',
                array(
                    'id' => $_GET['id'],
                ),
            ),
        ), array(
            'associate' => true,
        ));
        if (empty($members)) {
            warning('編集データが見つかりません。');
        } else {
            $view['member'] = $members[0];
        }
    }

    if (isset($_GET['type']) && $_GET['type'] === 'json') {
        //名簿情報を取得
        header('Content-Type: application/json; charset=' . MAIN_CHARSET);

        echo json_encode(array(
            'status' => 'OK',
            'data'   => $view,
            'files'  => array(
                'image_01' => $view['member']['image_01'] ? file_mimetype($view['member']['image_01']) : null,
                'image_02' => $view['member']['image_02'] ? file_mimetype($view['member']['image_02']) : null,
            ),
        ));

        exit;
    } else {
        //投稿セッションを初期化
        unset($_SESSION['post']);
        unset($_SESSION['file']);
    }

    //編集開始日時を記録
    if (!empty($_GET['id'])) {
        $_SESSION['update']['member'] = localdate('Y-m-d H:i:s');
    }
}

if ((empty($_POST['view']) || $_POST['view'] !== 'preview')) {
    //名簿の表示用データ作成
    $view['member'] = view_members($view['member']);
}

//教室を取得
$view['classes'] = select_classes(array(
    'order_by' => 'sort, id',
));

//分類を取得
$view['categories'] = select_categories(array(
    'order_by' => 'sort, id',
));

//タイトル
if (empty($_GET['id'])) {
    $view['title'] = '名簿登録';
} else {
    $view['title'] = '名簿編集';
}
