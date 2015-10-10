<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php t(MAIN_CHARSET) ?>" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/common.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/admin.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/upload.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/jquery.subwindow.css" />
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery-ui.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.subwindow.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.upload.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/common.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/admin.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/upload.js"></script>
		<title>アップロード</title>
	</head>
	<body>
		<h1>アップロード</h1>
		<?php if (isset($_GET['ok']) && $_GET['ok'] == 'post') : ?>
		<script>
		var file = '<?php t($view['target']) ?>';

		window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
		window.parent.$('#' + file + '_menu').show();
		window.parent.$.fn.subwindow.close();
		</script>
		<?php else : ?>
		<div id="upload">
			<p>ファイルを選択するか、ここにドラッグ＆ドロップしてください。</p>
			<form action="<?php t(MAIN_FILE) ?>/admin/class_image_upload?target=<?php t($view['target']) ?><?php !empty($_GET['id']) ? t('&id=' . intval($_GET['id'])) : '' ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<input type="hidden" name="type" value="json" />
				<input type="hidden" name="target" value="<?php t($view['target']) ?>" />
				<fieldset>
					<legend>アップロードフォーム</legend>
					<dl>
						<dt>ファイル</dt>
							<dd><input type="file" name="file" size="30" /></dd>
					</dl>
					<p><input type="submit" value="アップロードする" /></p>
				</fieldset>
			</form>
		</div>

		<?php if (isset($view['warnings'])) : ?>
		<ul class="warning">
			<?php foreach ($view['warnings'] as $warning) : ?>
			<li><?php h($warning) ?></li>
			<?php endforeach ?>
		</ul>
		<?php endif ?>

		<form action="<?php t(MAIN_FILE) ?>/admin/class_image_upload?target=<?php t($view['target']) ?><?php !empty($_GET['id']) ? t('&id=' . intval($_GET['id'])) : '' ?>" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend>アップロードフォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<dl>
					<dt>ファイル</dt>
						<dd><input type="file" name="file" size="30" /></dd>
				</dl>
				<p><input type="submit" value="アップロードする" /></p>
			</fieldset>
		</form>
		<?php endif ?>
	</body>
</html>
