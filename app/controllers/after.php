<?php

//ワンタイムトークン
if (isset($_POST['view'])) {
    $token_name = $_POST['view'];
} elseif (isset($_GET['view'])) {
    $token_name = $_GET['view'];
} else {
    $token_name = 'default';
}
$view['token'] = token('create', $token_name);
