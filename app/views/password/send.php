<?php import('app/views/header.php') ?>

		<h2><?php h($view['title']) ?></h2>

		<p>パスワード再発行ページのURLを送信しました。</p>
		<p>暗証コードは <code><?php h($_SESSION['token_code']) ?></code> です。</p>

<?php import('app/views/footer.php') ?>
