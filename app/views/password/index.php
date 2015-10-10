<?php import('app/views/header.php') ?>

		<h2>パスワード再発行</h2>

		<?php if (isset($view['warnings'])) : ?>
		<ul class="warning">
			<?php foreach ($view['warnings'] as $warning) : ?>
			<li><?php h($warning) ?></li>
			<?php endforeach ?>
		</ul>
		<?php endif ?>

		<form action="<?php t(MAIN_FILE) ?>/password" method="post" class="validate">
			<fieldset>
				<legend>再発行フォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<dl>
					<dt>メールアドレス</dt>
						<dd><input type="text" name="email" size="30" value="<?php t($view['user']['email']) ?>" /></dd>
				</dl>
				<p><input type="submit" value="再発行する" /></p>
			</fieldset>
		</form>

<?php import('app/views/footer.php') ?>
