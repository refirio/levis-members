<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>
        <dl>
            <dt>ユーザ名</dt>
                <dd><?php h($_view['user']['username']) ?></dd>
            <dt>パスワード</dt>
                <dd><?php h(str_repeat('*', strlen($_view['user']['password']))) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($_view['user']['email']) ?></dd>
        </dl>
        <p><a href="<?php t(MAIN_FILE) ?>/register?referer=preview">修正する</a></p>

        <form action="<?php t(MAIN_FILE) ?>/register/preview" method="post">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <p><input type="submit" value="登録する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
