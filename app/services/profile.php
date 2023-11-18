<?php

import('app/services/log.php');

/**
 * プロフィールの登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_profile_insert($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'profiles', 'insert');

    // プロフィールを登録
    $resource = insert_profiles($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

    return $resource;
}

/**
 * プロフィールの編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_profile_update($queries, $options = array())
{
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $profiles = select_profiles(array(
            'where' => array(
                'id = :id AND modified > :update',
                array(
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ),
            ),
        ));
        if (!empty($profiles)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 操作ログの記録
    service_log_record(null, null, 'profiles', 'update');

    // プロフィールを編集
    $resource = update_profiles($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

    return $resource;
}

/**
 * プロフィールの削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_profile_delete($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'profiles', 'delete');

    // プロフィールを削除
    $resource = delete_profiles($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}
