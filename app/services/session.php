<?php

/**
 * セッションの取得
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function service_session_select($queries, $options = array())
{
    // セッションを取得
    $sessions = select_sessions($queries, $options);

    return $sessions;
}

/**
 * セッションの登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_session_insert($queries, $options = array())
{
    // セッションを登録
    $resource = insert_sessions($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

    return $resource;
}

/**
 * セッションの編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_session_update($queries, $options = array())
{
    // セッションを編集
    $resource = update_sessions($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

    return $resource;
}

/**
 * セッションの削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_session_delete($queries, $options = array())
{
    // セッションを削除
    $resource = delete_sessions($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}
