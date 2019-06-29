<?php

/**
 * 名簿の登録
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_member_insert($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'members', 'insert');

    // 名簿を登録
    $resource = insert_members($queries, $options);
    if (!$resource) {
        error('データを登録できません。');
    }

    return $resource;
}

/**
 * 名簿の編集
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_member_update($queries, $options = array())
{
    $options = array(
        'id'            => isset($options['id'])            ? $options['id']            : null,
        'category_sets' => isset($options['category_sets']) ? $options['category_sets'] : array(),
        'files'         => isset($options['files'])         ? $options['files']         : array(),
        'update'        => isset($options['update'])        ? $options['update']        : null,
    );

    // 最終編集日時を確認
    if (isset($options['id']) && isset($options['update']) && (!isset($queries['set']['modified']) || $queries['set']['modified'] !== false)) {
        $members = select_members(array(
            'where' => array(
                'id = :id AND modified > :update',
                array(
                    'id'     => $options['id'],
                    'update' => $options['update'],
                ),
            ),
        ));
        if (!empty($members)) {
            error('編集開始後にデータが更新されています。');
        }
    }

    // 操作ログの記録
    service_log_record(null, null, 'members', 'update');

    // 名簿を編集
    $resource = update_members($queries, $options);
    if (!$resource) {
        error('データを編集できません。');
    }

    return $resource;
}

/**
 * 名簿の削除
 *
 * @param array $queries
 * @param array $options
 *
 * @return resource
 */
function service_member_delete($queries, $options = array())
{
    // 操作ログの記録
    service_log_record(null, null, 'members', 'delete');

    // 名簿を削除
    $resource = delete_members($queries, $options);
    if (!$resource) {
        error('データを削除できません。');
    }

    return $resource;
}

/**
 * 名簿をエクスポート
 *
 * @return string
 */
function service_member_export()
{
    // 名簿を取得
    $members = select_members(array(
        'where'    => 'members.public = 1',
        'order_by' => 'members.id',
    ), array(
        'associate' => true,
    ));

    // CSV形式に整形
    $data  = mb_convert_encoding('"ID","登録日時","更新日時","削除","クラスID","名前","名前（フリガナ）","成績","生年月日","メールアドレス","電話番号","メモ","画像1","画像2","公開","クラス名","分類ID"', 'SJIS-WIN', 'UTF-8');
    $data .= "\n";

    foreach ($members as $member) {
        $flag = false;
        foreach ($member as $key => $value) {
            if ($flag) {
                $data .= ',';
            }

            if ($key === 'grade') {
                $value = $GLOBALS['config']['options']['member']['grades'][$value];
            } elseif ($key === 'public') {
                $value = $GLOBALS['config']['options']['member']['publics'][$value];
            } elseif ($key === 'category_sets') {
                $value = implode(',', $value);
            }

            $data .= '"' . ($value !== '' ? str_replace('"', '""', mb_convert_encoding($value, 'SJIS-WIN', 'UTF-8')) : '') . '"';

            $flag = true;
        }
        $data .= "\n";
    }

    return $data;
}

/**
 * 名簿をインポート
 *
 * @param string $filename
 * @param string $operation
 *
 * @return array
 */
function service_member_import($filename, $operation = 'insert')
{
    if ($fp = fopen($filename, 'r')) {
        $options = array(
            'grades'  => array_flip($GLOBALS['config']['options']['member']['grades']),
            'publics' => array_flip($GLOBALS['config']['options']['member']['publics']),
        );

        if ($operation === 'replace') {
            // 元データ削除
            $resource = db_delete(array(
                'delete_from' => DATABASE_PREFIX . 'members',
            ));
            if (!$resource) {
                error('データを削除できません。');
            }

            $resource = db_delete(array(
                'delete_from' => DATABASE_PREFIX . 'category_sets',
            ));
            if (!$resource) {
                error('データを削除できません。');
            }
        }

        // CSVファイルの一行目を無視
        $dummy = file_getcsv($fp);

        // CSVファイル読み込み
        $all_warnings = array();
        $i            = 1;
        while ($line = file_getcsv($fp)) {
            list($id, $created, $modified, $deleted, $class_id, $name, $name_kana, $grade, $birthday, $email, $tel, $memo, $image_01, $image_02, $public, $dummy, $category_sets) = $line;

            // 入力データを整理
            $post = array(
                'member' => normalize_members(array(
                    'id'        => mb_convert_encoding($id, 'UTF-8', 'SJIS-WIN'),
                    'created'   => mb_convert_encoding($created, 'UTF-8', 'SJIS-WIN'),
                    'modified'  => mb_convert_encoding($modified, 'UTF-8', 'SJIS-WIN'),
                    'deleted'   => mb_convert_encoding($deleted, 'UTF-8', 'SJIS-WIN'),
                    'class_id'  => mb_convert_encoding($class_id, 'UTF-8', 'SJIS-WIN'),
                    'name'      => mb_convert_encoding($name, 'UTF-8', 'SJIS-WIN'),
                    'name_kana' => mb_convert_encoding($name_kana, 'UTF-8', 'SJIS-WIN'),
                    'grade'     => $options['grades'][mb_convert_encoding($grade, 'UTF-8', 'SJIS-WIN')],
                    'birthday'  => mb_convert_encoding($birthday, 'UTF-8', 'SJIS-WIN'),
                    'email'     => mb_convert_encoding($email, 'UTF-8', 'SJIS-WIN'),
                    'tel'       => mb_convert_encoding($tel, 'UTF-8', 'SJIS-WIN'),
                    'memo'      => mb_convert_encoding($memo, 'UTF-8', 'SJIS-WIN'),
                    'image_01'  => mb_convert_encoding($image_01, 'UTF-8', 'SJIS-WIN'),
                    'image_02'  => mb_convert_encoding($image_02, 'UTF-8', 'SJIS-WIN'),
                    'public'    => $options['publics'][mb_convert_encoding($public, 'UTF-8', 'SJIS-WIN')],
                )),
            );

            // 入力データを検証＆登録
            $warnings = validate_members($post['member']);
            if (empty($warnings)) {
                if ($operation === 'update') {
                    // データ編集
                    $resource = db_update(array(
                        'update' => DATABASE_PREFIX . 'members',
                        'set'    => array(
                            'created'   => $post['member']['created'],
                            'modified'  => $post['member']['modified'],
                            'deleted'   => $post['member']['deleted'],
                            'class_id'  => $post['member']['class_id'],
                            'name'      => $post['member']['name'],
                            'name_kana' => $post['member']['name_kana'],
                            'grade'     => $post['member']['grade'],
                            'birthday'  => $post['member']['birthday'],
                            'email'     => $post['member']['email'],
                            'tel'       => $post['member']['tel'],
                            'memo'      => $post['member']['memo'],
                            'image_01'  => $post['member']['image_01'],
                            'image_02'  => $post['member']['image_02'],
                            'public'    => $post['member']['public'],
                        ),
                        'where'  => array(
                            'id = :id',
                            array(
                                'id' => $post['member']['id'],
                            ),
                        ),
                    ));
                    if (!$resource) {
                        error('データを編集できません。');
                    }
                } else {
                    // データ登録
                    $resource = db_insert(array(
                        'insert_into' => DATABASE_PREFIX . 'members',
                        'values'      => array(
                            'id'        => $post['member']['id'],
                            'created'   => $post['member']['created'],
                            'modified'  => $post['member']['modified'],
                            'deleted'   => $post['member']['deleted'],
                            'class_id'  => $post['member']['class_id'],
                            'name'      => $post['member']['name'],
                            'name_kana' => $post['member']['name_kana'],
                            'grade'     => $post['member']['grade'],
                            'birthday'  => $post['member']['birthday'],
                            'email'     => $post['member']['email'],
                            'tel'       => $post['member']['tel'],
                            'memo'      => $post['member']['memo'],
                            'image_01'  => $post['member']['image_01'],
                            'image_02'  => $post['member']['image_02'],
                            'public'    => $post['member']['public'],
                        ),
                    ));
                    if (!$resource) {
                        error('データを登録できません。');
                    }
                }

                if ($category_sets) {
                    // 分類を登録
                    $category_sets = explode(',', $category_sets);

                    foreach ($category_sets as $category_id) {
                        $resource = db_insert(array(
                            'insert_into' => DATABASE_PREFIX . 'category_sets',
                            'values'      => array(
                                'category_id' => $category_id,
                                'member_id'   => $id,
                            ),
                        ));
                        if (!$resource) {
                            return $resource;
                        }
                    }
                }
            } else {
                foreach ($warnings as $warning) {
                    $all_warnings[] = '[' . $i . '行目] ' . $warning;
                }
            }

            $i++;
        }

        fclose($fp);

        if (empty($all_warnings)) {
            return array();
        } else {
            return $all_warnings;
        }
    } else {
        return array('ファイルを読み込めません。');
    }
}
