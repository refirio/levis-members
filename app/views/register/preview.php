<?php import('app/views/header.php') ?>

		<h2>ユーザ登録確認</h2>
		<dl>
			<dt>ユーザ名</dt>
				<dd><?php h($view['user']['username']) ?></dd>
			<dt>パスワード</dt>
				<dd><?php h($view['user']['password']) ?></dd>
			<dt>名前</dt>
				<dd><?php h($view['user']['name']) ?></dd>
			<dt>メールアドレス</dt>
				<dd><?php h($view['user']['email']) ?></dd>
			<dt>メモ</dt>
				<dd><?php h($view['user']['memo']) ?></dd>
		</dl>
		<p><a href="<?php t(MAIN_FILE) ?>/register?referer=preview">修正する</a></p>

		<form action="<?php t(MAIN_FILE) ?>/register/preview" method="post">
			<fieldset>
				<legend>登録フォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<p><input type="submit" value="登録する" /></p>
			</fieldset>
		</form>

<?php import('app/views/footer.php') ?>
