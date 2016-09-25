<?php

// ログアウト
unset($_SESSION['auth']['administrator']);

// リファラ
if (isset($_GET['referer'])) {
    $referer .= '?referer=' . urlencode($_GET['referer']);
} else {
    $referer = '';
}

// リダイレクト
redirect('/admin' . $referer);
