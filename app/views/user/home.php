<?php import('app/views/header.php') ?>

		<h2><?php h($view['title']) ?></h2>
		<ul>
			<li><a href="<?php t(MAIN_FILE) ?>/user/modify">ユーザ情報編集</a></li>
			<li><a href="<?php t(MAIN_FILE) ?>/user/twostep">2段階認証設定</a></li>
			<li><a href="<?php t(MAIN_FILE) ?>/user/password">パスワード再入力サンプル</a></li>
			<li><a href="<?php t(MAIN_FILE) ?>/user/logout">ログアウト</a></li>
		</ul>

<?php import('app/views/footer.php') ?>
