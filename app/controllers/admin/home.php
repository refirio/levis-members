<?php

//ログイン確認
if (empty($_SESSION['administrator'])) {
	redirect('/admin');
}
