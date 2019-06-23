<?php

/**
 * プロフィールの取得
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function service_profile_select($queries, $options = array())
{
    // プロフィールを取得
    $profiles = select_profiles($queries, $options);

    return $profiles;
}

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
    // プロフィールを削除
    $resource = delete_profiles($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}
