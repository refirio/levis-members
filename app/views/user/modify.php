<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/user/modify" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <dl>
                    <dt>ユーザ名</dt>
                        <dd><input type="text" name="username" size="30" value="<?php t($_view['user']['username']) ?>" /></dd>
                    <dt>パスワード（変更したい場合のみ入力）</dt>
                        <dd><input type="password" name="password" size="30" value="<?php t($_view['user']['password']) ?>" /></dd>
                    <dt>パスワード確認（同じものをもう一度入力）</dt>
                        <dd><input type="password" name="password_confirm" size="30" value="<?php t($_view['user']['password']) ?>" /></dd>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($_view['user']['email']) ?>" /></dd>
                    <dt>名前</dt>
                        <dd><input type="text" name="profile_name" size="30" value="<?php t($_view['profile']['name']) ?>" /></dd>
                    <dt>紹介文</dt>
                        <dd><textarea name="profile_text" rows="10" cols="50"><?php t($_view['profile']['text']) ?></textarea></dd>
                </dl>
                <p><input type="submit" value="確認する" /></p>
            </fieldset>
        </form>

        <h2>ユーザ情報削除</h2>
        <form action="<?php t(MAIN_FILE) ?>/user/delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <p><input type="submit" value="削除する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
