<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php t(MAIN_CHARSET) ?>" />
		<title>加工</title>
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/common.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/admin.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/trimming.css" />
		<link rel="stylesheet" href="<?php t($GLOBALS['http_path']) ?>css/jquery.subwindow.css" />
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery-ui.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.subwindow.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/jquery.upload.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/common.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/admin.js"></script>
		<script src="<?php t($GLOBALS['http_path']) ?>js/trimming.js"></script>
	</head>
	<body>
		<h1>加工</h1>
		<?php if (isset($_GET['ok']) && $_GET['ok'] == 'post') : ?>
		<script>
		var file = '<?php t($view['target']) ?>';

		window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
		window.parent.$('#' + file + '_menu').show();
		window.parent.$.fn.subwindow.close();
		</script>
		<?php else : ?>
		<div id="trimming">
			<div id="scope"></div>
		</div>
		<form action="<?php t(MAIN_FILE) ?>/admin/member_image_process?target=<?php t($view['target']) ?><?php $view['id'] ? t('&id=' . $view['id']) : '' ?>" method="post" class="trimming">
			<fieldset>
				<legend>実行フォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<input type="hidden" name="image" value="<?php t(MAIN_FILE) ?>/admin/member_image?target=<?php t($view['target']) ?><?php $view['id'] ? t('&id=' . $view['id']) : '' ?>" />
				<dl>
					<dt>位置とサイズ</dt>
					<dd>
						X: <input type="text" name="trimming[left]" size="5" value="" />
						Y: <input type="text" name="trimming[top]" size="5" value="" />
						幅: <input type="text" name="trimming[width]" size="5" value="" />
						高: <input type="text" name="trimming[height]" size="5" value="" />
					</dd>
				</dl>
				<p><input type="submit" value="実行する" /></p>
			</fieldset>
		</form>
		<?php endif ?>
	</body>
</html>
