<?php import('app/views/admin/header.php') ?>

    <h3><?php h($_view['title']) ?></h3>

    <?php if (isset($_view['warnings'])) : ?>
    <ul class="warning">
        <?php foreach ($_view['warnings'] as $warning) : ?>
        <li><?php h($warning) ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>

    <form action="<?php t(MAIN_FILE) ?>/admin/category_form<?php $_view['category']['id'] ? t('?id=' . $_view['category']['id']) : '' ?>" method="post" class="register validate">
        <fieldset>
            <legend>登録フォーム</legend>
            <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
            <input type="hidden" name="id" value="<?php t($_view['category']['id']) ?>" />
            <dl>
                <dt>名前</dt>
                    <dd><input type="text" name="name" size="30" value="<?php t($_view['category']['name']) ?>" /></dd>
            </dl>
            <p><input type="submit" value="登録する" /></p>
        </fieldset>
    </form>

    <?php if (!empty($_GET['id'])) : ?>
    <h3>分類削除</h3>
    <form action="<?php t(MAIN_FILE) ?>/admin/category_delete" method="post" class="delete">
        <fieldset>
            <legend>削除フォーム</legend>
            <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
            <input type="hidden" name="id" value="<?php t($_view['category']['id']) ?>" />
            <p><input type="submit" value="削除する" /></p>
        </fieldset>
    </form>
    <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
