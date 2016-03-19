<?php import('app/views/admin/header.php') ?>

        <h3>メニュー</h3>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/user_form">ユーザ登録</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/user">ユーザ一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/class_form">教室登録</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/class">教室一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/member_form">名簿登録</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/member">名簿一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/category_form">分類登録</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/category">分類一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/csv_download">CSVダウンロード</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/csv_upload">CSVアップロード</a></li>
        </ul>

<?php import('app/views/admin/footer.php') ?>
