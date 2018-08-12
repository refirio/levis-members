<?php import('app/views/admin/header.php') ?>

    <?php if (isset($_POST['view']) && $_POST['view'] === 'preview') : ?>
        <h3>確認</h3>
        <dl>
            <dt>教室</dt>
                <dd>
                    <?php foreach ($_view['classes'] as $class) : ?>
                        <?php if ($class['id'] === $_view['member']['class_id']) : ?>
                        <?php t($class['name']) ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </dd>
            <dt>名前</dt>
                <dd><?php h($_view['member']['name']) ?></dd>
            <dt>名前（フリガナ）</dt>
                <dd><?php h($_view['member']['name_kana']) ?></dd>
            <dt>成績</dt>
                <dd><?php h($GLOBALS['config']['options']['member']['grades'][$_view['member']['grade']]) ?></dd>
            <dt>生年月日</dt>
                <dd><?php h(localdate('Y年m月d日', $_view['member']['birthday'])) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($_view['member']['email']) ?></dd>
            <dt>電話番号</dt>
                <dd><?php h($_view['member']['tel']) ?></dd>
            <dt>メモ</dt>
                <dd><?php h($_view['member']['memo']) ?></dd>
            <dt>画像1</dt>
                <dd><img src="<?php t(MAIN_FILE) ?>/admin/file?_type=image&amp;target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>"></dd>
            <dt>画像2</dt>
                <dd><img src="<?php t(MAIN_FILE) ?>/admin/file?_type=image&amp;target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>"></dd>
            <dt>公開</dt>
                <dd><?php h($GLOBALS['config']['options']['member']['publics'][$_view['member']['public']]) ?></dd>
        </dl>
        <p><a href="#" class="close">閉じる</a></p>
    <?php else : ?>
        <h3><?php h($_view['title']) ?></h3>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/member_form<?php $_view['member']['id'] ? t('?id=' . $_view['member']['id']) : '' ?>" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <input type="hidden" name="id" value="<?php t($_view['member']['id']) ?>">
                <input type="hidden" name="view" value="">
                <dl>
                    <dt>教室</dt>
                        <dd>
                            <select name="class_id">
                                <option value=""></option>
                                <?php foreach ($_view['classes'] as $class) : ?>
                                <option value="<?php t($class['id']) ?>"<?php $class['id'] === $_view['member']['class_id'] ? e(' selected="selected"') : '' ?>><?php t($class['name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                    <dt>分類</dt>
                        <dd>
                            <div id="validate_category_sets">
                                <?php foreach ($_view['categories'] as $category) : ?>
                                <label><input type="checkbox" name="category_sets[]" value="<?php t($category['id']) ?>"<?php in_array($category['id'], array_column($_view['member']['category_sets'], 'category_id')) ? e(' checked="checked"') : '' ?>> <?php t($category['name']) ?></label><br>
                                <?php endforeach ?>
                            </div>
                        </dd>
                    <dt>名前</dt>
                        <dd><input type="text" name="name" size="30" value="<?php t($_view['member']['name']) ?>"></dd>
                    <dt>名前（フリガナ）</dt>
                        <dd><input type="text" name="name_kana" size="30" value="<?php t($_view['member']['name_kana']) ?>"></dd>
                    <dt>成績</dt>
                        <dd>
                            <select name="grade">
                                <?php foreach ($GLOBALS['config']['options']['member']['grades'] as $key => $value) : ?>
                                <option value="<?php t($key) ?>"<?php $key == $_view['member']['grade'] ? e(' selected="selected"') : '' ?>><?php t($value) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                    <dt>生年月日</dt>
                        <dd>
                            <div id="validate_birthday">
                                <select name="birthday[year]">
                                    <option value=""></option>
                                    <?php e(ui_datetime($_view['member']['birthday'], 'year', array('suffix' => '年', 'from' => localdate('Y') - 20, 'to' => localdate('Y') - 10))) ?>
                                </select>
                                <select name="birthday[month]">
                                    <option value=""></option>
                                    <?php e(ui_datetime($_view['member']['birthday'], 'month', array('suffix' => '月'))) ?>
                                </select>
                                <select name="birthday[day]">
                                    <option value=""></option>
                                    <?php e(ui_datetime($_view['member']['birthday'], 'day', array('suffix' => '日'))) ?>
                                </select>
                            </div>
                        </dd>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($_view['member']['email']) ?>"></dd>
                    <dt>電話番号</dt>
                        <dd>
                            <div id="validate_tel">
                                <input type="text" name="tel[]" size="10" value="<?php t($_view['member']['tel'][0]) ?>">
                                -
                                <input type="text" name="tel[]" size="10" value="<?php t($_view['member']['tel'][1]) ?>">
                                -
                                <input type="text" name="tel[]" size="10" value="<?php t($_view['member']['tel'][2]) ?>">
                            </div>
                        </dd>
                    <dt>メモ</dt>
                        <dd><textarea name="memo" rows="10" cols="50"><?php t($_view['member']['memo']) ?></textarea></dd>
                    <dt>画像1</dt>
                        <dd class="upload">
                            <a href="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?_type=image&amp;target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" id="image_01"></a>
                            <div class="file_menu" id="image_01_menu">
                                <ul>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_process?view=subwindow&amp;target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="ファイル加工" class="file_process">加工</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=member&amp;key=image_01&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" id="image_01_delete" class="token" data-token="<?php t($_view['token']) ?>">削除</a></li>
                                </ul>
                            </div>
                        </dd>
                    <dt>画像2</dt>
                        <dd class="upload">
                            <a href="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?_type=image&amp;target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" id="image_02"></a>
                            <div class="file_menu" id="image_02_menu">
                                <ul>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_process?view=subwindow&amp;target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="ファイル加工" class="file_process">加工</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=member&amp;key=image_02&amp;format=image<?php $_view['member']['id'] ? t('&id=' . $_view['member']['id']) : '' ?>" id="image_02_delete" class="token" data-token="<?php t($_view['token']) ?>">削除</a></li>
                                </ul>
                            </div>
                        </dd>
                    <dt>公開</dt>
                        <dd>
                            <select name="public">
                                <?php foreach ($GLOBALS['config']['options']['member']['publics'] as $key => $value) : ?>
                                <option value="<?php t($key) ?>"<?php $key == $_view['member']['public'] ? e(' selected="selected"') : '' ?>><?php t($value) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                </dl>
                <p>
                    <input type="button" value="確認する" class="preview">
                    <input type="submit" value="登録する">
                </p>
            </fieldset>
        </form>

        <?php if (!empty($_GET['id'])) : ?>
        <h3>名簿削除</h3>
        <form action="<?php t(MAIN_FILE) ?>/admin/member_delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <input type="hidden" name="id" value="<?php t($_view['member']['id']) ?>">
                <p><input type="submit" value="削除する"></p>
            </fieldset>
        </form>
        <?php endif ?>
    <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
