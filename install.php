<?php
$_API_KEY = "fbd5c3cbab93be668fb4c298aad6c8b6";
$_NGROK_URI = "https://405c-2405-201-4003-aa5d-ca7-1793-1578-452d.ngrok-free.app";
$shop = $_GET['shop'];
$scopes = "read_products,write_products,read_orders,write_orders";
$redirect_uri = $_NGROK_URI . "/yudhshadow/token.php";
$nonce = bin2hex(random_bytes(12));
$access_mode = "per-user";


$oauth_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $_API_KEY . "&scope=" . $scopes . '&redirect_uri=' . urlencode($redirect_uri) . "&state=" . $nonce . "&grant_options[]=" . $access_mode;


header("location:" . $oauth_url);
exit();
