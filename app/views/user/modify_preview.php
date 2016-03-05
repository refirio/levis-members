<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>
        <dl>
            <dt>ユーザ名</dt>
                <dd><?php h($view['user']['username']) ?></dd>
            <dt>パスワード</dt>
                <dd><?php h(str_repeat('*', strlen($view['user']['password']))) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($view['user']['email']) ?></dd>
            <dt>名前</dt>
                <dd><?php h($view['profile']['name']) ?></dd>
            <dt>紹介文</dt>
                <dd><?php h($view['profile']['text']) ?></dd>
        </dl>
        <p><a href="<?php t(MAIN_FILE) ?>/user/modify?referer=preview">修正する</a></p>

        <form action="<?php t(MAIN_FILE) ?>/user/modify_preview" method="post">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />
                <p><input type="submit" value="登録する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
