<?php

/**
 * 教室の登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_class_insert($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'classes', 'insert');

    // 教室を登録
    $resource = insert_classes($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

    return $resource;
}

/**
 * 教室の編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_class_update($queries, $options = array())
{
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $classes = select_classes(array(
            'where' => array(
                'id = :id AND modified > :update',
                array(
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ),
            ),
        ));
        if (!empty($classes)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 操作ログの記録
    service_log_record(null, null, 'classes', 'update');

    // 教室を編集
    $resource = update_classes($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

    return $resource;
}

/**
 * 教室の削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_class_delete($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'classes', 'delete');

    // 教室を削除
    $resource = delete_classes($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}

/**
 * 教室の並び順を一括変更
 *
 * @param array $data
 *
 * @return void
 */
function service_class_sort($data)
{
    // 並び順を更新
    foreach ($data as $id => $sort) {
        if (!preg_match('/^[\w\-\/]+$/', $id)) {
            continue;
        }
        if (!preg_match('/^\d+$/', $sort)) {
            continue;
        }

        $resource = service_class_update(array(
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
