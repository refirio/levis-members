<?php

$view['script'] = '<script src="' . t($GLOBALS['config']['http_path'], true) . 'js/search.js"></script>' . "\n";

?>
<?php import('app/views/header.php') ?>

        <h2><?php h($view['title']) ?></h2>
        <form action="<?php t(MAIN_FILE) ?>/select/view" method="get" class="search">
            <fieldset>
                <legend>選択フォーム</legend>
                <dl>
                    <dt>教室</dt>
                        <dd>
                            <select name="class_id">
                                <option value="">選択してください</option>
                                <?php foreach ($view['classes'] as $class) : ?>
                                <option value="<?php t($class['id']) ?>"><?php t($class['name']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                    <dt>名簿</dt>
                        <dd>
                            <select name="id">
                                <option value="">教室を選択してください</option>
                            </select>
                        </dd>
                </dl>
                <p><input type="submit" value="表示する" /></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
