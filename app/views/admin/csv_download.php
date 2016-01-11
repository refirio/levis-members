<?php import('app/views/admin/header.php') ?>

		<h3><?php h($view['title']) ?></h3>

		<ul>
			<li>現在公開中の名簿を、CSV形式でダウンロードします。</li>
		</ul>

		<form action="<?php t(MAIN_FILE) ?>/admin/csv_download" method="post">
			<fieldset>
				<legend>ダウンロードフォーム</legend>
				<p><input type="submit" value="ダウンロードする" /></p>
			</fieldset>
		</form>

<?php import('app/views/admin/footer.php') ?>
