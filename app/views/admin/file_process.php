<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php t(MAIN_CHARSET) ?>" />
        <title>加工</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/common.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/admin.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/trimming.css" />
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/jquery.subwindow.css" />
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery-ui.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.subwindow.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.upload.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/common.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/admin.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/trimming.js"></script>
    </head>
    <body>
        <h1>加工</h1>
        <?php if (isset($_GET['ok']) && $_GET['ok'] === 'post') : ?>
        <script>
        var file = '<?php t($_view['key']) ?>';

        window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
        window.parent.$('#' + file + '_menu').show();
        window.parent.$.fn.subwindow.close();
        </script>
        <?php else : ?>
        <div id="trimming">
            <div id="scope"></div>
        </div>
        <form action="<?php t(MAIN_FILE) ?>/admin/file_process?view=subwindow&amp;target=<?php t($_view['target']) ?>&amp;key=<?php t($_view['key']) ?>&amp;format=<?php t($_view['format']) ?><?php $_view['id'] ? t('&id=' . $_view['id']) : '' ?>" method="post" class="trimming">
            <fieldset>
                <legend>実行フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token" />
                <input type="hidden" name="image" value="<?php t(MAIN_FILE) ?>/admin/file?target=<?php t($_view['target']) ?>&amp;key=<?php t($_view['key']) ?>&amp;format=<?php t($_view['format']) ?><?php $_view['id'] ? t('&id=' . $_view['id']) : '' ?>" />
                <dl>
                    <dt>位置とサイズ</dt>
                    <dd>
                        X: <input type="text" name="trimming[left]" size="5" value="" />
                        Y: <input type="text" name="trimming[top]" size="5" value="" />
                        幅: <input type="text" name="trimming[width]" size="5" value="" />
                        高: <input type="text" name="trimming[height]" size="5" value="" />
                    </dd>
                </dl>
                <p><input type="submit" value="実行する" /></p>
            </fieldset>
        </form>
        <?php endif ?>
    </body>
</html>
