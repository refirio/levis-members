<?php

import('libs/plugins/validator.php');
import('libs/plugins/file.php');
import('libs/plugins/directory.php');

/**
 * 教室の取得
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function select_classes($queries, $options = array())
{
    $queries = db_placeholder($queries);

    //教室を取得
    $queries['from'] = DATABASE_PREFIX . 'classes';

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
 * 教室の登録
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function insert_classes($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'files' => isset($options['files']) ? $options['files'] : array(),
    );

    //初期値を取得
    $defaults = default_classes();

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
    $queries['insert_into'] = DATABASE_PREFIX . 'classes';

    $resource = db_insert($queries);
    if (!$resource) {
        return $resource;
    }

    //IDを取得
    $id = db_last_insert_id();

    if (!empty($options['files'])) {
        //関連するファイルを削除
        remove_classes($id, $options['files']);

        //関連するファイルを保存
        save_classes($id, $options['files']);
    }

    return $resource;
}

/**
 * 教室の編集
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function update_classes($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'id'     => isset($options['id'])     ? $options['id']     : null,
        'update' => isset($options['update']) ? $options['update'] : null,
        'files'  => isset($options['files'])  ? $options['files']  : array(),
    );

    //最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $classes = db_select(array(
            'from'  => DATABASE_PREFIX . 'classes',
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

    //初期値を取得
    $defaults = default_classes();

    if (isset($queries['set']['modified'])) {
        if ($queries['set']['modified'] === false) {
            unset($queries['set']['modified']);
        }
    } else {
        $queries['set']['modified'] = $defaults['modified'];
    }

    //データを編集
    $queries['update'] = DATABASE_PREFIX . 'classes';

    $resource = db_update($queries);
    if (!$resource) {
        return $resource;
    }

    //IDを取得
    $id = $options['id'];

    if (!empty($options['files'])) {
        //関連するファイルを削除
        remove_classes($id, $options['files']);

        //関連するファイルを保存
        save_classes($id, $options['files']);
    }

    return $resource;
}

/**
 * 教室の削除
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function delete_classes($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true,
        'associate'  => isset($options['associate'])  ? $options['associate']  : false,
        'file'       => isset($options['file'])       ? $options['file']       : false,
    );

    //削除するデータのIDを取得
    $classes = db_select(array(
        'select' => 'id',
        'from'   => DATABASE_PREFIX . 'classes AS classes',
        'where'  => isset($queries['where']) ? $queries['where'] : '',
        'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
    ));

    $deletes = array();
    foreach ($classes as $class) {
        $deletes[] = intval($class['id']);
    }

    if ($options['associate'] === true) {
        //関連するデータを削除
        $resource = delete_members(array(
            'where' => 'class_id IN(' . implode($deletes) . ')',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    if ($options['softdelete'] === true) {
        //データを編集
        $resource = db_update(array(
            'update' => DATABASE_PREFIX . 'classes AS classes',
            'set'    => array(
                'deleted' => localdate('Y-m-d H:i:s'),
                'code'    => array('CONCAT(\'DELETED ' . localdate('YmdHis') . ' \', code)'),
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
            'delete_from' => DATABASE_PREFIX . 'classes AS classes',
            'where'       => isset($queries['where']) ? $queries['where'] : '',
            'limit'       => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    if ($options['file'] === true) {
        //関連するファイルを削除
        foreach ($deletes as $delete) {
            directory_rmdir($GLOBALS['file_targets']['class'] . $delete . '/');
        }
    }

    return $resource;
}

/**
 * 教室の正規化
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function normalize_classes($queries, $options = array())
{
    //並び順
    if (isset($queries['sort'])) {
        $queries['sort'] = mb_convert_kana($queries['sort'], 'n', MAIN_INTERNAL_ENCODING);
    } else {
        if (!$queries['id']) {
            $classes = db_select(array(
                'select' => 'MAX(sort) AS sort',
                'from'   => DATABASE_PREFIX . 'classes',
            ));
            $queries['sort'] = $classes[0]['sort'] + 1;
        }
    }

    return $queries;
}

/**
 * 教室の検証
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function validate_classes($queries, $options = array())
{
    $options = array(
        'duplicate' => isset($options['duplicate']) ? $options['duplicate'] : true,
    );

    $messages = array();

    //コード
    if (isset($queries['code'])) {
        if (!validator_required($queries['code'])) {
            $messages['code'] = 'コードが入力されていません。';
        } elseif (!validator_alpha_dash($queries['code'])) {
            $messages['code'] = 'コードは半角英数字で入力してください。';
        } elseif (!validator_max_length($queries['code'], 20)) {
            $messages['code'] = 'コードは20文字以内で入力してください。';
        } elseif ($options['duplicate'] === true) {
            if ($queries['id']) {
                $classes = db_select(array(
                    'select' => 'id',
                    'from'   => DATABASE_PREFIX . 'classes',
                    'where'  => array(
                        'id != :id AND code = :code',
                        array(
                            'id'   => $queries['id'],
                            'code' => $queries['code'],
                        ),
                    ),
                ));
            } else {
                $classes = db_select(array(
                    'select' => 'id',
                    'from'   => DATABASE_PREFIX . 'classes',
                    'where'  => array(
                        'code = :code',
                        array(
                            'code' => $queries['code'],
                        ),
                    ),
                ));
            }
            if (!empty($classes)) {
                $messages['code'] = '入力されたコードはすでに使用されています。';
            }
        }
    }

    //名前
    if (isset($queries['name'])) {
        if (!validator_required($queries['name'])) {
            $messages['name'] = '名前が入力されていません。';
        } elseif (!validator_max_length($queries['name'], 20)) {
            $messages['name'] = '名前は20文字以内で入力してください。';
        }
    }

    //メモ
    if (isset($queries['memo'])) {
        if (!validator_required($queries['memo'])) {
        } elseif (!validator_max_length($queries['memo'], 1000)) {
            $messages['memo'] = 'メモは1000文字以内で入力してください。';
        }
    }

    //並び順
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
 * ファイルの保存
 *
 * @param  string  $id
 * @param  array  $files
 * @return void
 */
function save_classes($id, $files)
{
    foreach (array_keys($files) as $file) {
        if (empty($files[$file]['delete']) && !empty($files[$file]['name'])) {
            if (preg_match('/\.(.*)$/', $files[$file]['name'], $matches)) {
                $directory = $GLOBALS['file_targets']['class'] . intval($id) . '/';
                $filename  = $file . '.' . $matches[1];

                directory_mkdir($directory);

                if (file_put_contents($directory . $filename, $files[$file]['data']) === false) {
                    error('ファイル ' . $filename . ' を保存できません。');
                } else {
                    $resource = db_update(array(
                        'update' => DATABASE_PREFIX . 'classes',
                        'set'    => array(
                            $file => $filename,
                        ),
                        'where'  => array(
                            'id = :id',
                            array(
                                'id' => $id,
                            ),
                        ),
                    ));
                    if (!$resource) {
                        error('データを編集できません。');
                    }

                    file_resize($directory . $filename, $directory . 'thumbnail_' . $filename, $GLOBALS['resize_width'], $GLOBALS['resize_height'], $GLOBALS['resize_quality']);
                }
            } else {
                error('ファイル ' . $files[$file]['name'] . ' の拡張子を取得できません。');
            }
        }
    }
}

/**
 * ファイルの削除
 *
 * @param  string  $id
 * @param  array  $files
 * @return void
 */
function remove_classes($id, $files)
{
    foreach (array_keys($files) as $file) {
        if (!empty($files[$file]['delete']) || !empty($files[$file]['name'])) {
            $classes = db_select(array(
                'select' => $file,
                'from'   => DATABASE_PREFIX . 'classes',
                'where'  => array(
                    'id = :id',
                    array(
                        'id' => $id,
                    ),
                ),
            ));
            if (empty($classes)) {
                error('編集データが見つかりません。');
            } else {
                $class = $classes[0];
            }

            if (is_file($GLOBALS['file_targets']['class'] . intval($id) . '/' . $class[$file])) {
                if (is_file($GLOBALS['file_targets']['class'] . intval($id) . '/thumbnail_' . $class[$file])) {
                    unlink($GLOBALS['file_targets']['class'] . intval($id) . '/thumbnail_' . $class[$file]);
                }
                unlink($GLOBALS['file_targets']['class'] . intval($id) . '/' . $class[$file]);

                $resource = db_update(array(
                    'update' => DATABASE_PREFIX . 'classes',
                    'set'    => array(
                        $file => null,
                    ),
                    'where'  => array(
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
        }
    }
}

/**
 * 教室の初期値
 *
 * @return array
 */
function default_classes()
{
    return array(
        'id'       => null,
        'created'  => localdate('Y-m-d H:i:s'),
        'modified' => localdate('Y-m-d H:i:s'),
        'deleted'  => null,
        'code'     => '',
        'name'     => '',
        'memo'     => null,
        'image_01' => null,
        'image_02' => null,
        'document' => null,
        'sort'     => 0,
    );
}
