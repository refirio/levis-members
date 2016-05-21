<?php

/**
 * 分類の並び順を一括変更
 *
 * @param  array  $data
 * @return void
 */
function service_category_sort($data)
{
    //並び順を更新
    foreach ($data as $id => $sort) {
        if (!preg_match('/^[\w\-\/]+$/', $id)) {
            continue;
        }
        if (!preg_match('/^\d+$/', $sort)) {
            continue;
        }

        $resource = update_categories(array(
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

/**
 * 分類の並び順を変更
 *
 * @param  string  $id
 * @param  string  $target
 * @return void
 */
function service_category_move($id, $target)
{
    //移動元のidとsortを取得
    $category_from = select_categories(array(
        'select' => 'id, sort',
        'where'  => array(
            'id = :id',
            array(
                'id' => $id,
            ),
        ),
    ));
    $category_from = $category_from[0];

    //移動先のidとsortを取得
    if ($target === 'up') {
        $category_to = select_categories(array(
            'select'   => 'id, sort',
            'where'    => array(
                'sort < :sort',
                array(
                    'sort' => $category_from['sort'],
                ),
            ),
            'order_by' => 'sort DESC',
            'limit'    => 1,
        ));
        $category_to = $category_to[0];
    } else {
        $category_to = select_categories(array(
            'select'   => 'id, sort',
            'where'    => array(
                'sort > :sort',
                array(
                    'sort' => $category_from['sort'],
                )
            ),
            'order_by' => 'sort',
            'limit'    => 1,
        ));
        $category_to = $category_to[0];
    }

    if (empty($category_to)) {
        error('移動元データを取得できません。');
    }

    //移動元と移動先のidとsortを入れ替え
    $resource = update_categories(array(
        'set'   => array(
            'sort' => $category_to['sort'],
        ),
        'where' => array(
            'id = :id',
            array(
                'id' => $category_from['id'],
            ),
        ),
    ));
    if (!$resource) {
        error('移動元データを編集できません。');
    }

    $resource = update_categories(array(
        'set'   => array(
            'sort' => $category_from['sort'],
        ),
        'where' => array(
            'id = :id',
            array(
                'id' => $category_to['id'],
            ),
        ),
    ));
    if (!$resource) {
        error('移動先データを編集できません。');
    }

    return;
}
