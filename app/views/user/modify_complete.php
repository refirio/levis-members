<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>

        <p>ユーザ情報を編集しました。</p>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/user/home">戻る</a></li>
        </ul>

<?php import('app/views/footer.php') ?>
