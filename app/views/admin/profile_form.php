<?php import('app/views/admin/header.php') ?>

    <?php if (isset($_POST['view']) && $_POST['view'] === 'preview') : ?>
        <h3>確認</h3>
        <dl>
            <dt>名前</dt>
                <dd><?php h($_view['profile']['name']) ?></dd>
            <dt>紹介文</dt>
                <dd><?php h($_view['profile']['text']) ?></dd>
            <dt>メモ</dt>
                <dd><?php h($_view['profile']['memo']) ?></dd>
        </dl>
        <p><a href="#" class="close">閉じる</a></p>
    <?php else : ?>
        <h3><?php h($_view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/user_form?id=<?php t($_view['profile']['user_id']) ?>">ユーザ情報</a></li>
            <li><em>プロフィール</em></li>
        </ul>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/profile_form?id=<?php t($_view['profile']['user_id']) ?>" method="post" class="register validate">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <input type="hidden" name="id" value="<?php t($_view['profile']['id']) ?>">
                <input type="hidden" name="view" value="">
                <dl>
                    <dt>名前</dt>
                        <dd><input type="text" name="name" size="30" value="<?php t($_view['profile']['name']) ?>"></dd>
                    <dt>紹介文</dt>
                        <dd><textarea name="text" rows="10" cols="50"><?php t($_view['profile']['text']) ?></textarea></dd>
                    <dt>メモ</dt>
                        <dd><textarea name="memo" rows="10" cols="50"><?php t($_view['profile']['memo']) ?></textarea></dd>
                </dl>
                <p>
                    <input type="button" value="確認する" class="preview">
                    <input type="submit" value="登録する">
                </p>
            </fieldset>
        </form>
    <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
