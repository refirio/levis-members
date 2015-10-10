<?php

//ログアウト
unset($_SESSION['administrator']);

redirect('/admin');
