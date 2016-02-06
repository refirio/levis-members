<?php import('app/views/admin/header.php') ?>

        <h3><?php h($view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/class_form">教室登録</a></li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] == 'post') : ?>
            <li>データを登録しました。</li>
            <?php elseif ($_GET['ok'] == 'sort') : ?>
            <li>データを並び替えました。</li>
            <?php elseif ($_GET['ok'] == 'delete') : ?>
            <li>データを削除しました。</li>
            <?php endif ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/class_sort" method="post" id="sortable">
            <fieldset>
                <legend>並び替えフォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />

                <table summary="教室一覧">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>コード</th>
                            <th>名前</th>
                            <th>画像1</th>
                            <th>画像2</th>
                            <th>資料</th>
                            <?php
                            /*
                            <th>並び順</th>
                            <th>並び替え</th>
                            */
                            ?>
                            <th>並び替え</th>
                            <th>作業</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>コード</th>
                            <th>名前</th>
                            <th>画像1</th>
                            <th>画像2</th>
                            <th>資料</th>
                            <?php
                            /*
                            <th>並び順</th>
                            <th>並び替え</th>
                            */
                            ?>
                            <th>並び替え</th>
                            <th>作業</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($view['classes'] as $class) : ?>
                        <tr id="sort_<?php h($class['id']) ?>">
                            <td><?php h($class['id']) ?></td>
                            <td><?php h($class['code']) ?></td>
                            <td><?php h($class['name']) ?></td>
                            <td><?php h($class['image_01']) ?></td>
                            <td><?php h($class['image_02']) ?></td>
                            <td><?php h($class['document']) ?></td>
                            <?php
                            /*
                            <td><?php h($class['sort']) ?></td>
                            <td><?php

                                if ($view['class_sorts']['min'] != $class['sort']) {
                                    e('<a href="' . t(MAIN_FILE, true) . '/admin/class_sort?id=' . t($class['id'], true) . '&amp;target=up&amp;token=' . t($view['token'], true) . '">↑</a>');
                                }

                                h(' ');

                                if ($view['class_sorts']['max'] != $class['sort']) {
                                    e('<a href="' . t(MAIN_FILE, true) . '/admin/class_sort?id=' . t($class['id'], true) . '&amp;target=down&amp;token=' . t($view['token'], true) . '">↓</a>');
                                }

                            ?></td>
                            */
                            ?>
                            <td><span class="handle">並び替え</span></td>
                            <?php
                            /*
                            <td><input type="text" name="sort[<?php t($class['id']) ?>]" size="3" value="<?php t($class['sort']) ?>" /></td>
                            */
                            ?>
                            <td><a href="<?php t(MAIN_FILE) ?>/admin/class_form?id=<?php t($class['id']) ?>">編集</a></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <?php
                /*
                <p><input type="submit" value="並び順を編集する" /></p>
                */
                ?>

            </fieldset>
        </form>

<?php import('app/views/admin/footer.php') ?>
