<?php import('app/views/header.php') ?>

        <h2>警告</h2>

        <ul class="warning">
            <?php foreach ($_view['messages'] as $message) : ?>
            <li><?php h($message) ?></li>
            <?php endforeach ?>
        </ul>

<?php import('app/views/footer.php') ?>
