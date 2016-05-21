<?php

import('libs/plugins/validator.php');

/**
 * プロフィールの取得
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function select_profiles($queries, $options = array())
{
    $queries = db_placeholder($queries);

    //プロフィールを取得
    $queries['from'] = DATABASE_PREFIX . 'profiles';

    //削除済みデータは取得しない
    if (!isset($queries['where'])) {
        $queries['where'] = 'TRUE';
    }
    $queries['where'] = 'deleted IS NULL AND (' . $queries['where'] . ')';

    //データを取得
    $results = db_select($queries);

    return $results;
}

/**
 * プロフィールの登録
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function insert_profiles($queries, $options = array())
{
    $queries = db_placeholder($queries);

    //初期値を取得
    $defaults = default_profiles();

    if (isset($queries['values']['created'])) {
        if ($queries['values']['created'] === false) {
            unset($queries['values']['created']);
        }
    } else {
        $queries['values']['created'] = $defaults['created'];
    }
    if (isset($queries['values']['modified'])) {
        if ($queries['values']['modified'] === false) {
            unset($queries['values']['modified']);
        }
    } else {
        $queries['values']['modified'] = $defaults['modified'];
    }

    //データを登録
    $queries['insert_into'] = DATABASE_PREFIX . 'profiles';

    $resource = db_insert($queries);
    if (!$resource) {
        return $resource;
    }

    return $resource;
}

/**
 * プロフィールの編集
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function update_profiles($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    //最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $profiles = db_select(array(
            'from'  => DATABASE_PREFIX . 'profiles',
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

    //初期値を取得
    $defaults = default_profiles();

    if (isset($queries['set']['modified'])) {
        if ($queries['set']['modified'] === false) {
            unset($queries['set']['modified']);
        }
    } else {
        $queries['set']['modified'] = $defaults['modified'];
    }

    //データを編集
    $queries['update'] = DATABASE_PREFIX . 'profiles';

    $resource = db_update($queries);
    if (!$resource) {
        return $resource;
    }

    return $resource;
}

/**
 * プロフィールの削除
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function delete_profiles($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true,
        'associate'  => isset($options['associate'])  ? $options['associate']  : false,
    );

    if ($options['softdelete'] === true) {
        //データを編集
        $resource = db_update(array(
            'update' => DATABASE_PREFIX . 'profiles AS profiles',
            'set'    => array(
                'deleted' => localdate('Y-m-d H:i:s'),
            ),
            'where'  => isset($queries['where']) ? $queries['where'] : '',
            'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    } else {
        //データを削除
        $resource = db_delete(array(
            'delete_from' => DATABASE_PREFIX . 'profiles AS profiles',
            'where'       => isset($queries['where']) ? $queries['where'] : '',
            'limit'       => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    return $resource;
}

/**
 * プロフィールの検証
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function validate_profiles($queries, $options = array())
{
    $options = array(
        'duplicate' => isset($options['duplicate']) ? $options['duplicate'] : true,
    );

    $messages = array();

    //名前
    if (isset($queries['name'])) {
        if (!validator_required($queries['name'])) {
        } elseif (!validator_max_length($queries['name'], 20)) {
            $messages['name'] = '名前は20文字以内で入力してください。';
        }
    }

    //紹介文
    if (isset($queries['text'])) {
        if (!validator_required($queries['text'])) {
        } elseif (!validator_max_length($queries['text'], 1000)) {
            $messages['text'] = '紹介文は1000文字以内で入力してください。';
        }
    }

    //メモ
    if (isset($queries['memo'])) {
        if (!validator_required($queries['memo'])) {
        } elseif (!validator_max_length($queries['memo'], 1000)) {
            $messages['memo'] = 'メモは1000文字以内で入力してください。';
        }
    }

    return $messages;
}

/**
 * プロフィールの初期値
 *
 * @return array
 */
function default_profiles()
{
    return array(
        'id'       => null,
        'created'  => localdate('Y-m-d H:i:s'),
        'modified' => localdate('Y-m-d H:i:s'),
        'deleted'  => null,
        'user_id'  => 0,
        'name'     => null,
        'text'     => null,
        'memo'     => null,
    );
}
