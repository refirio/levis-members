<?php if (isset($GLOBALS['config'])) : ?>
<?php import('app/views/header.php') ?>
<?php else : ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>">
        <title>Error</title>
    </head>
    <body>
        <h1>Error</h1>
<?php endif ?>

        <h2>エラー</h2>

        <ul class="error">
            <li><?php h($_view['message']) ?></li>
        </ul>
        <p><a href="<?php t(empty($_SERVER['HTTP_REFERER']) ? MAIN_FILE . '/' : $_SERVER['HTTP_REFERER']) ?>">戻る</a></p>

<?php if (isset($GLOBALS['config'])) : ?>
<?php import('app/views/footer.php') ?>
<?php else : ?>
    </body>
</html>
<?php endif ?>
