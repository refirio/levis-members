<?php import('app/views/admin/header.php') ?>

    <?php if (isset($_POST['view']) && $_POST['view'] === 'preview') : ?>
        <h3>確認</h3>
        <dl>
            <dt>ユーザ名</dt>
                <dd><?php h($_view['user']['username']) ?></dd>
            <dt>パスワード</dt>
                <dd><?php h(str_repeat('*', strlen($_view['user']['password']))) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($_view['user']['email']) ?></dd>
        </dl>
        <p><a href="#" class="close">閉じる</a></p>
    <?php else : ?>
        <h3><?php h($_view['title']) ?></h3>

        <?php if (!empty($_GET['id'])) : ?>
        <ul>
            <li><em>ユーザ情報</em></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/profile_form?user_id=<?php t($_view['user']['id']) ?>">プロフィール</a></li>
        </ul>
        <?php endif ?>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/user_form<?php $_view['user']['id'] ? t('?id=' . $_view['user']['id']) : '' ?>" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <input type="hidden" name="id" value="<?php t($_view['user']['id']) ?>" />
                <input type="hidden" name="view" value="" />
                <dl>
                    <dt>ユーザ名</dt>
                        <dd><input type="text" name="username" size="30" value="<?php t($_view['user']['username']) ?>" /></dd>
                    <dt>パスワード<?php if (!empty($_GET['id'])) : ?>（変更したい場合のみ入力）<?php endif ?></dt>
                        <dd><input type="password" name="password" size="30" value="" /></dd>
                    <dt>パスワード確認（同じものをもう一度入力）</dt>
                        <dd><input type="password" name="password_confirm" size="30" value="" /></dd>
                    <dt>メールアドレス</dt>
                        <dd><input type="text" name="email" size="30" value="<?php t($_view['user']['email']) ?>" /></dd>
                </dl>
                <p>
                    <input type="button" value="確認する" class="preview" />
                    <input type="submit" value="登録する" />
                </p>
            </fieldset>
        </form>

        <?php if (!empty($_GET['id'])) : ?>
        <h3>ユーザ削除</h3>
        <form action="<?php t(MAIN_FILE) ?>/admin/user_delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <input type="hidden" name="id" value="<?php t($_view['user']['id']) ?>" />
                <p><input type="submit" value="削除する" /></p>
            </fieldset>
        </form>
        <?php endif ?>
    <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
