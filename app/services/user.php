<?php

import('app/services/session.php');
import('app/services/log.php');
import('libs/plugins/cookie.php');

/**
 * ユーザの登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_user_insert($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'users', 'insert');

    // ユーザを登録
    $resource = insert_users($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

    return $resource;
}

/**
 * ユーザの編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_user_update($queries, $options = array())
{
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $users = select_users(array(
            'where' => array(
                'id = :id AND modified > :update',
                array(
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ),
            ),
        ));
        if (!empty($users)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 操作ログの記録
    service_log_record(null, null, 'users', 'update');

    // ユーザを編集
    $resource = update_users($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

    return $resource;
}

/**
 * ユーザの削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_user_delete($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'users', 'delete');

    // ユーザを削除
    $resource = delete_users($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}

/**
 * ユーザのオートログイン
 *
 * @param string $session_id
 *
 * @return array
 */
function service_user_autologin($session_id)
{
    // セッションを取得
    $users = select_sessions(array(
        'select' => 'user_id, keep',
        'where'  => array(
            'id = :id AND expire > :expire',
            array(
                'id'     => $session_id,
                'expire' => localdate('Y-m-d H:i:s'),
            ),
        ),
    ));

    $session = false;
    $user_id = null;

    if (!empty($users)) {
        // セッションを更新
        $new_session_id = rand_string();

        $resource = service_session_update(array(
            'set'   => array(
                'id'     => $new_session_id,
                'agent'  => $_SERVER['HTTP_USER_AGENT'],
                'expire' => localdate('Y-m-d H:i:s', time() + $GLOBALS['config']['cookie_expire']),
            ),
            'where' => array(
                'id = :id',
                array(
                    'id' => $session_id,
                ),
            ),
        ));
        if ($resource) {
            cookie_set('auth[session]', $new_session_id, time() + $GLOBALS['config']['cookie_expire'], $GLOBALS['config']['cookie_path'], $GLOBALS['config']['cookie_domain'], $GLOBALS['config']['cookie_secure']);
        } else {
            error('データを編集できません。');
        }

        if ($users[0]['keep']) {
            // ユーザを更新
            $resource = service_user_update(array(
                'set'   => array(
                    'loggedin' => localdate('Y-m-d H:i:s'),
                ),
                'where' => array(
                    'id = :id',
                    array(
                        'id' => $users[0]['user_id'],
                    ),
                ),
            ));
            if (!$resource) {
                error('データを編集できません。');
            }

            $session = true;
            $user_id = $users[0]['user_id'];
        }
    }

    return array($session, $user_id);
}
