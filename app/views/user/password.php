<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <?php if (empty($_SESSION['auth']['password'])) : ?>
        <form action="<?php t(MAIN_FILE) ?>/user/password" method="post">
            <fieldset>
                <legend>認証フォーム</legend>
                <dl>
                    <dt>パスワード</dt>
                        <dd><input type="password" name="password" size="30" value="<?php t(isset($_POST['password']) ? $_POST['password'] : '') ?>" /></dd>
                </dl>
                <p><input type="submit" value="認証する" /></p>
            </fieldset>
        </form>
        <?php else : ?>
        <p>認証に成功しました。</p>
        <?php endif ?>

<?php import('app/views/footer.php') ?>
