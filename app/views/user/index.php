<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>

        <?php if (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <?php if (empty($view['twostep'])) : ?>
        <form action="<?php t(MAIN_FILE) ?>/user" method="post">
            <fieldset>
                <legend>認証フォーム</legend>
                <dl>
                    <dt>ユーザ名</dt>
                        <dd><input type="text" name="username" size="30" value="<?php t($view['user']['username']) ?>" /></dd>
                    <dt>パスワード</dt>
                        <dd><input type="password" name="password" size="30" value="<?php t($view['user']['password']) ?>" /></dd>
                </dl>
                <ul>
                    <li><label><input type="checkbox" name="session" value="keep"<?php isset($view['user']['session']) ? e('checked="checked"') : '' ?> /> ログイン状態を記憶する</label></li>
                </ul>
                <p><input type="submit" value="認証する" /></p>
            </fieldset>
        </form>
        <?php else : ?>
        <form action="<?php t(MAIN_FILE) ?>/user" method="post">
            <fieldset>
                <legend>認証フォーム</legend>
                <input type="hidden" name="username" value="<?php t($view['user']['username']) ?>" />
                <input type="hidden" name="password" value="<?php t($view['user']['password']) ?>" />
                <input type="hidden" name="session" value="<?php t($view['user']['session']) ?>" />
                <dl>
                    <dt>2段階認証用コード</dt>
                        <dd><input type="text" name="twostep_code" size="30" value="" /></dd>
                </dl>
                <ul>
                    <li><label><input type="checkbox" name="twostep_session" value="keep"<?php isset($view['user']['twostep_session']) ? e('checked="checked"') : '' ?> /> 次回からコード入力ウインドウを表示しない</label></li>
                </ul>
                <p><input type="submit" value="認証する" /></p>
            </fieldset>
        </form>
        <form action="<?php t(MAIN_FILE) ?>/user" method="post">
            <fieldset>
                <legend>認証フォーム</legend>
                <input type="hidden" name="username" value="<?php t($view['user']['username']) ?>" />
                <input type="hidden" name="password" value="<?php t($view['user']['password']) ?>" />
                <input type="hidden" name="session" value="<?php t($view['user']['session']) ?>" />
                <p><input type="submit" value="コードの再送信" /></p>
            </fieldset>
        </form>
        <?php endif ?>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/register">ユーザ登録</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/password">パスワード再発行</a></li>
        </ul>

<?php import('app/views/footer.php') ?>
