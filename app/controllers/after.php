<?php

// ワンタイムトークン
if (isset($_POST['view'])) {
    $token_name = $_POST['view'];
} elseif (isset($_GET['view'])) {
    $token_name = $_GET['view'];
} else {
    $token_name = 'default';
}
$_view['token'] = token('create', $token_name);
