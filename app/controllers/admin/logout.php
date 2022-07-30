<?php

// ログアウト
unset($_SESSION['auth']['administrator']);

// リファラ
if (isset($_GET['referer'])) {
    $referer = '?referer=' . rawurlencode($_GET['referer']);
} else {
    $referer = '';
}

// リダイレクト
redirect('/admin' . $referer);
