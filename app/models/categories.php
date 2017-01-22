<?php

import('libs/plugins/validator.php');

/**
 * 分類の取得
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function select_categories($queries, $options = array())
{
    $queries = db_placeholder($queries);

    // 分類を取得
    $queries['from'] = DATABASE_PREFIX . 'categories';

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
 * 分類の登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function insert_categories($queries, $options = array())
{
    $queries = db_placeholder($queries);

    // 初期値を取得
    $defaults = default_categories();

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
    $queries['insert_into'] = DATABASE_PREFIX . 'categories';

    $resource = db_insert($queries);
    if (!$resource) {
        return $resource;
    }

    // 操作ログの記録
    service_log_record(null, 'categories', 'insert');

    return $resource;
}

/**
 * 分類の編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function update_categories($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $categories = db_select(array(
            'from'  => DATABASE_PREFIX . 'categories',
            'where' => array(
                'id = :id AND modified > :update',
                array(
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ),
            ),
        ));
        if (!empty($categories)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 初期値を取得
    $defaults = default_categories();

    if (isset($queries['set']['modified'])) {
        if ($queries['set']['modified'] === false) {
            unset($queries['set']['modified']);
        }
    } else {
        $queries['set']['modified'] = $defaults['modified'];
    }

    // データを編集
    $queries['update'] = DATABASE_PREFIX . 'categories';

    $resource = db_update($queries);
    if (!$resource) {
        return $resource;
    }

    // 操作ログの記録
    service_log_record(null, 'categories', 'update');

    return $resource;
}

/**
 * 分類の削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function delete_categories($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true,
        'associate'  => isset($options['associate'])  ? $options['associate']  : false,
    );

    // 削除するデータのIDを取得
    $categories = db_select(array(
        'select' => 'id',
        'from'   => DATABASE_PREFIX . 'categories AS categories',
        'where'  => isset($queries['where']) ? $queries['where'] : '',
        'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
    ));

    $deletes = array();
    foreach ($categories as $category) {
        $deletes[] = intval($category['id']);
    }

    if ($options['associate'] === true) {
        // 関連するデータを削除
        $resource = delete_category_sets(array(
            'where' => 'category_id IN(' . implode($deletes) . ')',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    if ($options['softdelete'] === true) {
        // データを編集
        $resource = db_update(array(
            'update' => DATABASE_PREFIX . 'categories AS categories',
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
        // データを削除
        $resource = db_delete(array(
            'delete_from' => DATABASE_PREFIX . 'categories AS categories',
            'where'       => isset($queries['where']) ? $queries['where'] : '',
            'limit'       => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    // 操作ログの記録
    service_log_record(null, 'categories', 'delete');

    return $resource;
}

/**
 * 分類の正規化
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function normalize_categories($queries, $options = array())
{
    // 並び順
    if (isset($queries['sort'])) {
        $queries['sort'] = mb_convert_kana($queries['sort'], 'n', MAIN_INTERNAL_ENCODING);
    } else {
        if (!$queries['id']) {
            $categories = db_select(array(
                'select' => 'MAX(sort) AS sort',
                'from'   => DATABASE_PREFIX . 'categories',
            ));
            $queries['sort'] = $categories[0]['sort'] + 1;
        }
    }

    return $queries;
}

/**
 * 分類の検証
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function validate_categories($queries, $options = array())
{
    $options = array(
        'duplicate' => isset($options['duplicate']) ? $options['duplicate'] : true,
    );

    $messages = array();

    // 名前
    if (isset($queries['name'])) {
        if (!validator_required($queries['name'])) {
            $messages['name'] = '名前が入力されていません。';
        } elseif (!validator_max_length($queries['name'], 20)) {
            $messages['name'] = '名前は20文字以内で入力してください。';
        }
    }

    // 並び順
    if (isset($queries['sort'])) {
        if (!validator_required($queries['sort'])) {
            $messages['sort'] = '並び順が入力されていません。';
        } elseif (!validator_numeric($queries['sort'])) {
            $messages['sort'] = '並び順は半角数字で入力してください。';
        } elseif (!validator_max_length($queries['sort'], 5)) {
            $messages['sort'] = '並び順は5桁以内で入力してください。';
        }
    }

    return $messages;
}

/**
 * 分類の初期値
 *
 * @return array
 */
function default_categories()
{
    return array(
        'id'       => null,
        'created'  => localdate('Y-m-d H:i:s'),
        'modified' => localdate('Y-m-d H:i:s'),
        'deleted'  => null,
        'name'     => '',
        'sort'     => 0,
    );
}
