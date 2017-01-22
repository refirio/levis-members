<?php

/**
 * 操作ログの記録
 *
 * @param string $model
 * @param string $exec
 *
 * @return bool
 */
function service_log_record($model = null, $exec = null, $message = null)
{
    global $_params;

    static $recorded = array(
        'model' => array(),
        'exec'  => array(),
    );

    if (isset($recorded['model'][$model]) && isset($recorded['exec'][$exec])) {
        return;
    } else {
        $recorded['model'][$model] = true;
        $recorded['exec'][$exec]   = true;
    }

    // ユーザ
    if (empty($_SESSION['auth']['user']['id'])) {
        $user_id = null;
    } else {
        $user_id = $_SESSION['auth']['user']['id'];
    }

    // 管理者
    if (empty($_SESSION['auth']['administrator']['id'])) {
        $administrator = null;
    } else {
        $administrator = $_SESSION['auth']['administrator']['id'];
    }

    // IPアドレス
    $ip = clientip($GLOBALS['config']['proxy']);

    // ユーザエージェント
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        $agent = null;
    } else {
        $agent = $_SERVER['HTTP_USER_AGENT'];
    }

    // ページ
    $page = '/' . implode('/', $_params);
    if (!empty($_SERVER['QUERY_STRING'])) {
        $page .= '?' . $_SERVER['QUERY_STRING'];
    }

    // 操作ログを登録
    $resource = insert_logs(array(
        'values' => array(
            'user_id'       => $user_id,
            'administrator' => $administrator,
            'ip'            => $ip,
            'agent'         => $agent,
            'model'         => $model,
            'exec'          => $exec,
            'message'       => $message,
            'page'          => $page,
        ),
    ));
    if (!$resource) {
        error('データを登録できません。');
    }

    return;
}
