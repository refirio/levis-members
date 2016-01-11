<?php

//ログアウト
unset($_SESSION['administrator']);

//リダイレクト
redirect('/admin');
