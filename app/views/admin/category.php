<?php import('app/views/admin/header.php') ?>

        <h3><?php h($view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/category_form">分類登録</a></li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] === 'post') : ?>
            <li>データを登録しました。</li>
            <?php elseif ($_GET['ok'] === 'sort') : ?>
            <li>データを並び替えました。</li>
            <?php elseif ($_GET['ok'] === 'delete') : ?>
            <li>データを削除しました。</li>
            <?php endif ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/category_sort" method="post" id="sortable">
            <fieldset>
                <legend>並び替えフォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />

                <table summary="分類一覧">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名前</th>
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
                            <th>名前</th>
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
                        <?php foreach ($view['categories'] as $category) : ?>
                        <tr id="sort_<?php h($category['id']) ?>">
                            <td><?php h($category['id']) ?></td>
                            <td><?php h($category['name']) ?></td>
                            <?php
                            /*
                            <td><?php h($category['sort']) ?></td>
                            <td><?php

                                if ($view['category_sorts']['min'] !== $category['sort']) {
                                    e('<a href="' . t(MAIN_FILE, true) . '/admin/category_sort?id=' . t($category['id'], true) . '&amp;target=up&amp;token=' . t($view['token'], true) . '">↑</a>');
                                }

                                h(' ');

                                if ($view['category_sorts']['max'] !== $category['sort']) {
                                    e('<a href="' . t(MAIN_FILE, true) . '/admin/category_sort?id=' . t($category['id'], true) . '&amp;target=down&amp;token=' . t($view['token'], true) . '">↓</a>');
                                }

                            ?></td>
                            */
                            ?>
                            <td><span class="handle">並び替え</span></td>
                            <?php
                            /*
                            <td><input type="text" name="sort[<?php t($category['id']) ?>]" size="3" value="<?php t($category['sort']) ?>" /></td>
                            */
                            ?>
                            <td><a href="<?php t(MAIN_FILE) ?>/admin/category_form?id=<?php t($category['id']) ?>">編集</a></td>
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