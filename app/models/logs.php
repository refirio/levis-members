<?php

import('libs/plugins/validator.php');

/**
 * 操作ログの取得
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function select_logs($queries, $options = array())
{
    $queries = db_placeholder($queries);

    // 操作ログを取得
    $queries['from'] = DATABASE_PREFIX . 'logs';

    // 削除済みデータは取得しない
    if (!isset($queries['where'])) {
        $queries['where'] = 'TRUE';
    }
    $queries['where'] = 'deleted IS NULL AND (' . $queries['where'] . ')';

    // データを取得
    $results = db_select($queries);

    return $results;
}

/**
 * 操作ログの登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function insert_logs($queries, $options = array())
{
    $queries = db_placeholder($queries);

    // 初期値を取得
    $defaults = default_logs();

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

    // データを登録
    $queries['insert_into'] = DATABASE_PREFIX . 'logs';

    $resource = db_insert($queries);

    return $resource;
}

/**
 * 操作ログの編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function update_logs($queries, $options = array())
{
    $queries = db_placeholder($queries);

    // 初期値を取得
    $defaults = default_logs();

    if (isset($queries['set']['modified'])) {
        if ($queries['set']['modified'] === false) {
            unset($queries['set']['modified']);
        }
    } else {
        $queries['set']['modified'] = $defaults['modified'];
    }

    // データを編集
    $queries['update'] = DATABASE_PREFIX . 'logs';

    $resource = db_update($queries);

    return $resource;
}

/**
 * 操作ログの削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function delete_logs($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true,
    );

    if ($options['softdelete'] === true) {
        // データを編集
        $resource = db_update(array(
            'update' => DATABASE_PREFIX . 'logs AS logs',
            'set'    => array(
                'deleted' => localdate('Y-m-d H:i:s'),
            ),
            'where'  => isset($queries['where']) ? $queries['where'] : '',
            'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
        ));
    } else {
        // データを削除
        $resource = db_delete(array(
            'delete_from' => DATABASE_PREFIX . 'logs AS logs',
            'where'       => isset($queries['where']) ? $queries['where'] : '',
            'limit'       => isset($queries['limit']) ? $queries['limit'] : '',
        ));
    }

    return $resource;
}

/**
 * 操作ログの検証
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function validate_logs($queries, $options = array())
{
    $options = array(
        'duplicate' => isset($options['duplicate']) ? $options['duplicate'] : true,
    );

    $messages = array();

    // IPアドレス
    if (isset($queries['ip'])) {
        if (!validator_required($queries['ip'])) {
            $messages['ip'] = 'IPアドレスが入力されていません。';
        }
    }

    // ページ
    if (isset($queries['page'])) {
        if (!validator_required($queries['page'])) {
            $messages['page'] = 'ページが入力されていません。';
        }
    }

    return $messages;
}

/**
 * 操作ログの初期値
 *
 * @return array
 */
function default_logs()
{
    return array(
        'id'            => null,
        'created'       => localdate('Y-m-d H:i:s'),
        'modified'      => localdate('Y-m-d H:i:s'),
        'deleted'       => null,
        'user_id'       => null,
        'administrator' => null,
        'ip'            => '',
        'agent'         => null,
        'model'         => null,
        'exec'          => null,
        'message'       => null,
        'page'          => '',
    );
}
