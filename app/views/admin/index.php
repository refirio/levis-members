<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin<?php empty($_GET['referer']) ? '' : t('?referer=' . rawurlencode($_GET['referer'])) ?>" method="post">
            <fieldset>
                <legend>認証フォーム</legend>
                <dl>
                    <dt>ユーザ名</dt>
                        <dd><input type="text" name="username" size="30" value="<?php t($_view['administrator']['username']) ?>" /></dd>
                    <dt>パスワード</dt>
                        <dd><input type="password" name="password" size="30" value="<?php t($_view['administrator']['password']) ?>" /></dd>
                </dl>
                <p><input type="submit" value="認証する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
