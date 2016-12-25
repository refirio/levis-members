<?php import('app/views/header.php') ?>

        <h2>エラー</h2>

        <ul class="error">
            <li><?php h($_view['message']) ?></li>
        </ul>
        <p><a href="<?php t(empty($_SERVER['HTTP_REFERER']) ? MAIN_FILE . '/' : $_SERVER['HTTP_REFERER']) ?>">戻る</a></p>

<?php import('app/views/footer.php') ?>
