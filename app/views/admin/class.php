<?php import('app/views/admin/header.php') ?>

        <h3><?php h($_view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/class_form">教室登録</a></li>
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

        <form action="<?php t(MAIN_FILE) ?>/admin/class_sort" method="post" id="sortable">
            <fieldset>
                <legend>並び替えフォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />

                <table summary="教室一覧">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>コード</th>
                            <th>名前</th>
                            <th>画像1</th>
                            <th>画像2</th>
                            <th>資料</th>
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
                            <th>並び替え</th>
                            <th>作業</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($_view['classes'] as $class) : ?>
                        <tr id="sort_<?php h($class['id']) ?>">
                            <td><?php h($class['id']) ?></td>
                            <td><?php h($class['code']) ?></td>
                            <td><?php h($class['name']) ?></td>
                            <td><?php h($class['image_01']) ?></td>
                            <td><?php h($class['image_02']) ?></td>
                            <td><?php h($class['document']) ?></td>
                            <td><span class="handle">並び替え</span></td>
                            <td><a href="<?php t(MAIN_FILE) ?>/admin/class_form?id=<?php t($class['id']) ?>">編集</a></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            </fieldset>
        </form>

<?php import('app/views/admin/footer.php') ?>
