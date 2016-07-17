<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>

        <?php if (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/register/form" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="key" value="<?php t($view['key']) ?>" />
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" class="token" />
                <dl>
                    <dt>暗証コード</dt>
                        <dd><input type="text" name="token_code" size="30" value="<?php t($view['user']['token_code']) ?>" /></dd>
                    <dt>ユーザ名</dt>
                        <dd><input type="text" name="username" size="30" value="<?php t($view['user']['username']) ?>" /></dd>
                    <dt>パスワード</dt>
                        <dd><input type="password" name="password" size="30" value="<?php t($view['user']['password']) ?>" /></dd>
                    <dt>パスワード確認（同じものをもう一度入力）</dt>
                        <dd><input type="password" name="password_confirm" size="30" value="<?php t($view['user']['password']) ?>" /></dd>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($view['key']) ?>" disabled="disabled" /></dd>
                </dl>
                <p><input type="submit" value="確認する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
