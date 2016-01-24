<?php import('app/views/admin/header.php') ?>

	<?php if (isset($_POST['preview']) && $_POST['preview'] == 'yes') : ?>
		<h3>確認</h3>
		<dl>
			<dt>コード</dt>
				<dd><?php h($view['class']['code']) ?></dd>
			<dt>名前</dt>
				<dd><?php h($view['class']['name']) ?></dd>
			<dt>メモ</dt>
				<dd><?php h($view['class']['memo']) ?></dd>
			<dt>画像1</dt>
				<dd><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" /></dd>
			<dt>画像2</dt>
				<dd><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" /></dd>
			<dt>資料</dt>
				<dd><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" /></dd>
		</dl>
		<p><a href="#" class="close">閉じる</a></p>
	<?php else : ?>
		<h3><?php h($view['title']) ?></h3>

		<?php if (isset($view['warnings'])) : ?>
		<ul class="warning">
			<?php foreach ($view['warnings'] as $warning) : ?>
			<li><?php h($warning) ?></li>
			<?php endforeach ?>
		</ul>
		<?php endif ?>

		<form action="<?php t(MAIN_FILE) ?>/admin/class_form<?php $view['class']['id'] ? t('?id=' . $view['class']['id']) : '' ?>" method="post" class="validate">
			<fieldset>
				<legend>登録フォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<input type="hidden" name="id" value="<?php t($view['class']['id']) ?>" />
				<input type="hidden" name="preview" value="no" />
				<dl>
					<dt>コード</dt>
						<dd><input type="text" name="code" size="30" value="<?php t($view['class']['code']) ?>" /></dd>
					<dt>名前</dt>
						<dd><input type="text" name="name" size="30" value="<?php t($view['class']['name']) ?>" /></dd>
					<dt>メモ</dt>
						<dd><textarea name="memo" rows="10" cols="50"><?php t($view['class']['memo']) ?></textarea></dd>
					<dt>画像1</dt>
						<dd class="upload">
							<a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" id="image_01" /></a>
							<div class="file_menu" id="image_01_menu">
								<ul>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_process?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="ファイル加工" class="file_process">加工</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=class&amp;key=image_01&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>&amp;token=<?php t($view['token']) ?>" id="image_01_delete">削除</a></li>
								</ul>
							</div>
						</dd>
					<dt>画像2</dt>
						<dd class="upload">
							<a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" id="image_02" /></a>
							<div class="file_menu" id="image_02_menu">
								<ul>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_process?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="ファイル加工" class="file_process">加工</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=class&amp;key=image_02&amp;format=image<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>&amp;token=<?php t($view['token']) ?>" id="image_02_delete">削除</a></li>
								</ul>
							</div>
						</dd>
					<dt>資料</dt>
						<dd class="upload">
							<a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" id="document" /></a>
							<div class="file_menu" id="document_menu">
								<ul>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_process?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="ファイル加工" class="file_process">加工</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
									<li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=class&amp;key=document&amp;format=file<?php $view['class']['id'] ? t('&id=' . $view['class']['id']) : '' ?>&amp;token=<?php t($view['token']) ?>" id="document_delete">削除</a></li>
								</ul>
							</div>
						</dd>
				</dl>
				<p>
					<input type="button" value="確認する" class="preview" />
					<input type="submit" value="登録する" />
				</p>
			</fieldset>
		</form>

		<?php if (!empty($_GET['id'])) : ?>
		<h3>教室削除</h3>
		<ul>
			<li>教室を削除すると、その教室に属する名簿も削除されます。</li>
		</ul>
		<form action="<?php t(MAIN_FILE) ?>/admin/class_delete" method="post" class="delete">
			<fieldset>
				<legend>削除フォーム</legend>
				<input type="hidden" name="token" value="<?php t($view['token']) ?>" />
				<input type="hidden" name="id" value="<?php t($view['class']['id']) ?>" />
				<p><input type="submit" value="削除する" /></p>
			</fieldset>
		</form>
		<?php endif ?>
	<?php endif ?>

<?php import('app/views/admin/footer.php') ?>
