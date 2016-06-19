<?php

//ログアウト
unset($_SESSION['auth']['administrator']);

//リダイレクト
redirect('/admin');
