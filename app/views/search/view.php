<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>
        <dl>
            <dt>ID</dt>
                <dd><?php h($_view['member']['id']) ?></dd>
            <dt>教室</dt>
                <dd><?php h($_view['member']['class_name']) ?></dd>
            <dt>名前</dt>
                <dd><?php h($_view['member']['name']) ?></dd>
            <dt>メールアドレス</dt>
                <dd><?php h($_view['member']['email']) ?></dd>
        </dl>

<?php import('app/views/footer.php') ?>
