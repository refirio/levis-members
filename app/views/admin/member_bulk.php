<?php import('app/views/admin/header.php') ?>

        <h3><?php h($_view['title']) ?></h3>

        <?php if (empty($_view['members'])) : ?>
        <ul>
            <li>一括処理対象が選択されていません。</li>
        </ul>
        <p><a href="<?php t(MAIN_FILE) ?>/admin/member?page=<?php t($_POST['page']) ?>">戻る</a></p>
        <?php else : ?>
        <ul>
            <li>以下の名簿が削除されます。よろしければ削除ボタンを押してください。</li>
        </ul>
        <p><a href="<?php t(MAIN_FILE) ?>/admin/member?page=<?php t($_POST['page']) ?>">戻る</a></p>

        <form action="<?php t(MAIN_FILE) ?>/admin/member_delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <input type="hidden" name="page" value="<?php t($_POST['page']) ?>" />
                <?php foreach ($_view['member_bulks'] as $member_bulk) : ?>
                <input type="hidden" name="list[]" value="<?php t($member_bulk) ?>" />
                <?php endforeach ?>
                <p><input type="submit" value="削除する" /></p>
            </fieldset>
        </form>

        <table summary="名簿一覧">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像1</th>
                    <th>画像2</th>
                    <th>公開</th>
                    <th>教室</th>
                    <th>分類</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像1</th>
                    <th>画像2</th>
                    <th>公開</th>
                    <th>教室</th>
                    <th>分類</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($_view['members'] as $member) : ?>
                <tr>
                    <td><?php h($member['id']) ?></td>
                    <td><?php h($member['name']) ?></td>
                    <td><?php h($member['name_kana']) ?></td>
                    <td><?php h($GLOBALS['config']['options']['member']['grades'][$member['grade']]) ?></td>
                    <td><?php h(localdate('Y年m月d日', $member['birthday'])) ?></td>
                    <td><?php h($member['email']) ?></td>
                    <td><?php h($member['tel']) ?></td>
                    <td><?php h($member['image_01']) ?></td>
                    <td><?php h($member['image_02']) ?></td>
                    <td><?php h($GLOBALS['config']['options']['member']['publics'][$member['public']]) ?></td>
                    <td><?php h($_view['class_sets'][$member['class_id']]['name']) ?></td>
                    <td>
                        <?php foreach ($_view['category_sets'] as $category_sets) : if (in_array($category_sets['id'], $member['category_sets'])) : ?>
                        <?php h($category_sets['name']) ?><br />
                        <?php endif; endforeach ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
