<?php

/**
 * 分類の登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_category_insert($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'categories', 'insert');

    // 分類を登録
    $resource = insert_categories($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

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
function service_category_update($queries, $options = array())
{
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $categories = select_categories(array(
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

    // 操作ログの記録
    service_log_record(null, null, 'categories', 'update');

    // 分類を編集
    $resource = update_categories($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

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
function service_category_delete($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'categories', 'delete');

    // 分類を削除
    $resource = delete_categories($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}

/**
 * 分類の並び順を一括変更
 *
 * @param array $data
 *
 * @return void
 */
function service_category_sort($data)
{
    // 並び順を更新
    foreach ($data as $id => $sort) {
        if (!preg_match('/^[\w\-\/]+$/', $id)) {
            continue;
        }
        if (!preg_match('/^\d+$/', $sort)) {
            continue;
        }

        $resource = service_category_update(array(
            'set'   => array(
                'sort' => $sort,
            ),
            'where' => array(
                'id = :id',
                array(
                    'id' => $id,
                ),
            ),
        ));
        if (!$resource) {
            error('データを編集できません。');
        }
    }

    return;
}
