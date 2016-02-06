<?php import('app/views/header.php') ?>

        <h2>教室一覧</h2>
        <ul>
            <?php foreach ($view['classes'] as $class) : ?>
            <li><a href="<?php t(MAIN_FILE) ?>/class/<?php t($class['code']) ?>"><?php h($class['name']) ?></a></li>
            <?php endforeach ?>
        </ul>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/search">名簿検索</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/select">名簿選択</a></li>
        </ul>

<?php import('app/views/footer.php') ?>
