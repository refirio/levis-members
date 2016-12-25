<?php import('app/views/header.php') ?>

        <h2>警告</h2>

        <ul class="warning">
            <?php foreach ($_view['messages'] as $message) : ?>
            <li><?php h($message) ?></li>
            <?php endforeach ?>
        </ul>
        <p><a href="<?php t(empty($_SERVER['HTTP_REFERER']) ? MAIN_FILE . '/' : $_SERVER['HTTP_REFERER']) ?>">戻る</a></p>

<?php import('app/views/footer.php') ?>
