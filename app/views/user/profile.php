<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>

        <?php if (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/user/profile" method="post" class="validate">
            <fieldset>
                <legend>設定フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />
                <dl>
                    <dt>名前</dt>
                        <dd><input type="text" name="name" size="30" value="<?php t($view['profile']['name']) ?>" /></dd>
                    <dt>紹介文</dt>
                        <dd><textarea name="text" rows="10" cols="50"><?php t($view['profile']['text']) ?></textarea></dd>
                </dl>
                <p><input type="submit" value="設定する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
