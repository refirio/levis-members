<?php import('app/views/header.php') ?>

        <h2>エラー</h2>

        <ul class="error">
            <li><?php h($view['message']) ?></li>
        </ul>

<?php import('app/views/footer.php') ?>
