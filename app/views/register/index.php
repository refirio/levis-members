<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>

        <?php if (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/register" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" class="token" />
                <dl>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($view['user']['email']) ?>" /></dd>
                </dl>
                <p><input type="submit" value="仮登録する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
