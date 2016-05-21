<?php import('app/views/admin/header.php') ?>

        <h3><?php h($view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/member_form">名簿登録</a></li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] === 'post') : ?>
            <li>データを登録しました。</li>
            <?php elseif ($_GET['ok'] === 'delete') : ?>
            <li>データを削除しました。</li>
            <?php endif ?>
        </ul>
        <?php elseif (isset($_GET['warning'])) : ?>
        <ul class="warning">
            <?php if ($_GET['warning'] === 'delete') : ?>
            <li>データが選択されていません。</li>
            <?php endif ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/member" method="get">
            <fieldset>
                <legend>検索フォーム</legend>
                <dl>
                    <dt>教室</dt>
                        <dd>
                            <select name="class_id">
                                <option value="">選択してください</option>
                                <?php foreach ($view['classes'] as $class) : ?>
                                <option value="<?php t($class['id']) ?>"<?php $class['id'] === $_GET['class_id'] ? e(' selected="selected"') : '' ?>><?php t($class['name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                </dl>
                <p><input type="submit" value="表示する" /></p>
            </fieldset>
        </form>

        <form action="<?php t(MAIN_FILE) ?>/admin/member_delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />
                <input type="hidden" name="page" value="<?php t($_GET['page']) ?>" />

                <p><input type="submit" value="削除する" /></p>

                <table summary="名簿一覧">
                    <thead>
                        <tr>
                            <th><label><input type="checkbox" name="" value="" class="bulks" /> 選択</label></th>
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
                            <th>作業</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><label><input type="checkbox" name="" value="" class="bulks" /> 選択</label></th>
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
                            <th>作業</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($view['members'] as $member) : ?>
                        <tr>
                            <td><input type="checkbox" name="bulks[]" value="<?php h($member['id']) ?>"<?php isset($_SESSION['bulks'][$member['id']]) ? e('checked="checked"') : '' ?> class="bulk" /></td>
                            <td><?php h($member['id']) ?></td>
                            <td><?php h($member['name']) ?></td>
                            <td><?php h($member['name_kana']) ?></td>
                            <td><?php h($GLOBALS['options']['member']['grades'][$member['grade']]) ?></td>
                            <td><?php h(localdate('Y年m月d日', $member['birthday'])) ?></td>
                            <td><?php h($member['email']) ?></td>
                            <td><?php h($member['tel']) ?></td>
                            <td><?php h($member['image_01']) ?></td>
                            <td><?php h($member['image_02']) ?></td>
                            <td><?php h($GLOBALS['options']['member']['publics'][$member['public']]) ?></td>
                            <td><?php h($view['class_sets'][$member['class_id']]['name']) ?></td>
                            <td>
                                <?php foreach ($view['category_sets'] as $category_sets) : if (in_array($category_sets['id'], $member['category_sets'])) : ?>
                                <?php h($category_sets['name']) ?><br />
                                <?php endif; endforeach ?>
                            </td>
                            <td><a href="<?php t(MAIN_FILE) ?>/admin/member_form?id=<?php t($member['id']) ?>">編集</a></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            </fieldset>
        </form>

        <?php if ($view['member_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <ul>
                <li><?php if ($_GET['page'] > 1) : ?><a href="<?php t(MAIN_FILE) ?>/admin/member?class_id=<?php t($_GET['class_id']) ?>&amp;page=<?php t($_GET['page'] - 1) ?>">前のページ</a><?php else : ?>前のページ<?php endif ?></li>
                <li><?php if ($view['member_page'] > $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/member?class_id=<?php t($_GET['class_id']) ?>&amp;page=<?php t($_GET['page'] + 1) ?>">次のページ</a><?php else : ?>次のページ<?php endif ?></li>
            </ul>
            <ul>
                <?php for ($i = 1; $i <= $view['member_page']; $i++) : ?>
                <li><?php if ($i !== $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/member?class_id=<?php t($_GET['class_id']) ?>&amp;page=<?php t($i) ?>"><?php t($i) ?></a><?php else : ?><?php t($i) ?><?php endif ?></li>
                <?php endfor ?>
            </ul>
            <p><?php e($view['member_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
