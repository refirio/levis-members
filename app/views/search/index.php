<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <form action="<?php t(MAIN_FILE) ?>/search" method="get">
            <fieldset>
                <legend>検索フォーム</legend>
                <dl>
                    <dt>教室</dt>
                        <dd>
                            <?php foreach ($_view['classes'] as $class) : ?>
                            <label><input type="checkbox" name="class_id[]" value="<?php t($class['id']) ?>" <?php in_array($class['id'], $_GET['class_id']) ? e(' checked="checked"') : '' ?> /> <?php h($class['name']) ?></label>
                            <?php endforeach ?>
                        </dd>
                    <dt>分類</dt>
                        <dd>
                            <?php foreach ($_view['categories'] as $category) : ?>
                            <label><input type="checkbox" name="category_sets[]" value="<?php t($category['id']) ?>" <?php in_array($category['id'], $_GET['category_sets']) ? e(' checked="checked"') : '' ?> /> <?php h($category['name']) ?></label>
                            <?php endforeach ?>
                        </dd>
                    <dt>名前</dt>
                        <dd><input type="text" name="name" size="30" value="<?php t($_GET['name']) ?>" /></dd>
                    <dt>成績</dt>
                        <dd>
                            <select name="grade">
                                <option value=""></option>
                                <?php foreach ($GLOBALS['config']['options']['member']['grades'] as $key => $value) : ?>
                                <option value="<?php t($key) ?>"<?php "$key" === $_GET['grade'] ? e(' selected="selected"') : '' ?>><?php h($value) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($_GET['email']) ?>" /></dd>
                </dl>
                <p><input type="submit" value="検索する" /></p>
            </fieldset>
        </form>

        <table summary="名簿一覧">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($_view['members'] as $member) : ?>
                <tr>
                    <td><?php h($member['name']) ?></td>
                    <td><?php h($member['name_kana']) ?></td>
                    <td><?php h($GLOBALS['config']['options']['member']['grades'][$member['grade']]) ?></td>
                    <td><?php h(localdate('Y年m月d日', $member['birthday'])) ?></td>
                    <td><?php h($member['email']) ?></td>
                    <td><?php h($member['tel']) ?></td>
                    <td>
                        <?php if ($member['image_01']) : ?>
                        <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['member'] . $member['id'] . '/' . $member['image_01']) ?>" class="image">画像1</a>
                        <?php endif ?>
                        <?php if ($member['image_02']) : ?>
                        <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['member'] . $member['id'] . '/' . $member['image_02']) ?>" class="image">画像2</a>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($_view['member_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <p><?php e($_view['member_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/footer.php') ?>
