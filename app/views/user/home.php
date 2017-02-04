<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if ($_view['_user']['email_verified'] == 1 && isset($_GET['ok']) && $_GET['ok'] === 'verify') : ?>
            <p class="ok">メールアドレスの存在確認が完了しました。</p>
        <?php elseif ($_view['_user']['email_verified'] == 0) : ?>
            <form action="<?php t(MAIN_FILE) ?>/user/verify" method="post">
                <fieldset>
                    <legend>送信フォーム</legend>
                    <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'send') : ?>
                    <p class="ok">メールが送信されました。メール内にあるURLをクリックし、存在確認を完了してください。</p>
                    <p><input type="submit" value="再度送信する" /></p>
                    <?php else : ?>
                    <p class="warning">メールアドレスの存在確認を行ってください。</p>
                    <p><input type="submit" value="確認メールを送信する" /></p>
                    <?php endif ?>
                </fieldset>
            </form>
        <?php endif ?>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/user/modify">ユーザ情報編集</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user/twostep">2段階認証設定</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user/password">パスワード再入力サンプル</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/user/logout">ログアウト</a></li>
        </ul>

<?php import('app/views/footer.php') ?>
