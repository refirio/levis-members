<?php

/**
 * 教室の取得
 *
 * @param array $queries
 * @param array $options
 *
 * @return array
 */
function service_class_select($queries, $options = array())
{
    // 教室を取得
    $classes = select_classes($queries, $options);

    return $classes;
}

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
