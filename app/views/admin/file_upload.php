<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>">
        <title>アップロード</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('common.css')) ?>">
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('admin.css')) ?>">
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('upload.css')) ?>">
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_css('jquery.subwindow.css')) ?>">
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery-ui.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery.subwindow.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('jquery.upload.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('common.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('admin.js')) ?>"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?><?php t(loader_js('upload.js')) ?>"></script>
    </head>
    <body>
        <h1>アップロード</h1>
        <?php if (isset($_GET['ok']) && $_GET['ok'] === 'post') : ?>
        <script>
        var file = '<?php t($_view['key']) ?>';

        window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
        window.parent.$('#' + file + '_menu').show();
        window.parent.$.fn.subwindow.close();
        </script>
        <?php else : ?>
        <div id="upload">
            <p>ファイルを選択するか、ここにドラッグ＆ドロップしてください。</p>
            <form action="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=<?php t($_view['target']) ?>&amp;key=<?php t($_view['key']) ?>&amp;format=<?php t($_view['format']) ?><?php !empty($_GET['id']) ? t('&id=' . intval($_GET['id'])) : '' ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <input type="hidden" name="_type" value="json">
                <input type="hidden" name="target" value="<?php t($_view['target']) ?>">
                <input type="hidden" name="key" value="<?php t($_view['key']) ?>">
                <input type="hidden" name="format" value="<?php t($_view['format']) ?>">
                <fieldset>
                    <legend>アップロードフォーム</legend>
                    <dl>
                        <dt>ファイル</dt>
                            <dd><input type="file" name="file" size="30"></dd>
                    </dl>
                    <p><input type="submit" value="アップロードする"></p>
                </fieldset>
            </form>
        </div>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/file_upload?view=subwindow&amp;target=<?php t($_view['target']) ?>&amp;key=<?php t($_view['key']) ?>&amp;format=<?php t($_view['format']) ?><?php !empty($_GET['id']) ? t('&id=' . intval($_GET['id'])) : '' ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>アップロードフォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <dl>
                    <dt>ファイル</dt>
                        <dd><input type="file" name="file" size="30"></dd>
                </dl>
                <p><input type="submit" value="アップロードする"></p>
            </fieldset>
        </form>
        <?php endif ?>
    </body>
</html>
