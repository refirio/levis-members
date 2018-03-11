<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/password" method="post" class="register validate">
            <fieldset>
                <legend>再発行フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <dl>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($_view['user']['email']) ?>"></dd>
                </dl>
                <p><input type="submit" value="再発行する"></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
