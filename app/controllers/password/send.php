<?php

//暗証コードを確認
if (empty($_SESSION['token_code'])) {
	redirect('/password');
}
