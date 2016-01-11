<?php import('app/views/header.php') ?>

		<h2><?php h($view['title']) ?></h2>
		<dl>
			<dt>ID</dt>
				<dd><?php h($view['member']['id']) ?></dd>
			<dt>教室</dt>
				<dd><?php h($view['member']['class_name']) ?></dd>
			<dt>名前</dt>
				<dd><?php h($view['member']['name']) ?></dd>
			<dt>メールアドレス</dt>
				<dd><?php h($view['member']['email']) ?></dd>
		</dl>

<?php import('app/views/footer.php') ?>
