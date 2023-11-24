<?php

import('app/services/log.php');

/**
 * 教室の登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_class_insert($queries, $options = [])
{
    // 操作ログの記録
    service_log_record(null, null, 'classes', 'insert');

    // 教室を登録
    $resource = model('insert_classes', $queries, $options);
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
function service_class_update($queries, $options = [])
{
    $options = [
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'files'  => isset($options['files'])  ? $options['files']  : [],
        'update' => isset($options['update']) ? $options['update'] : null,
    ];

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $classes = model('select_classes', [
            'where' => [
                'id = :id AND modified > :update',
                [
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ],
            ],
        ]);
        if (!empty($classes)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 操作ログの記録
    service_log_record(null, null, 'classes', 'update');

    // 教室を編集
    $resource = model('update_classes', $queries, $options);
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
function service_class_delete($queries, $options = [])
{
    // 操作ログの記録
    service_log_record(null, null, 'classes', 'delete');

    // 教室を削除
    $resource = model('delete_classes', $queries, $options);
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

        $resource = service_class_update([
            'set'   => [
                'sort' => $sort,
            ],
            'where' => [
                'id = :id',
                [
                    'id' => $id,
                ],
            ],
        ]);
        if (!$resource) {
            error('データを編集できません。');
        }
    }

    return;
}
