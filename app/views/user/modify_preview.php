<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <dl>
            <dt>ユーザ名</dt>
                <dd><?php h($_view['user']['username']) ?></dd>
            <dt>パスワード</dt>
                <dd><?php h(str_repeat('*', strlen($_view['user']['password']))) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($_view['user']['email']) ?></dd>
            <dt>名前</dt>
                <dd><?php h($_view['profile']['name']) ?></dd>
            <dt>紹介文</dt>
                <dd><?php h($_view['profile']['text']) ?></dd>
        </dl>
        <p><a href="<?php t(MAIN_FILE) ?>/user/modify?referer=preview">修正する</a></p>

        <form action="<?php t(MAIN_FILE) ?>/user/modify_preview" method="post">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <p><input type="submit" value="登録する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
