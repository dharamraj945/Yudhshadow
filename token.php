<?php
include_once("includes/db_config.php");

$api_key = "fbd5c3cbab93be668fb4c298aad6c8b6";

$secret_key = "8bcb3546d8618f9768bb70fd243e0a28";
$parameters = $_GET;
$shop_url = $parameters['shop'];
$hmac = $parameters['hmac'];
$parameters = array_diff_key($parameters, array('hmac' => ''));
ksort($parameters);


$new_hmac = hash_hmac('sha256', http_build_query($parameters), $secret_key);

if (hash_equals($hmac, $new_hmac)) {

    $access_token_endpoint = "https://" . $shop_url . "/admin/oauth/access_token";
    $var = array(
        "client_id" => $api_key,
        "client_secret" => $secret_key,
        "code" => $parameters['code']
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($var));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($var));
    $response = curl_exec($ch);

    curl_close($ch);
    $response = json_decode($response, true);


    $check_store_exist = "SELECT * FROM `shops` WHERE shop_url= '$shop_url'";
    
    $check_store_exist_exec = $conn->query($check_store_exist);
    if (mysqli_num_rows($check_store_exist_exec) == 0) {
        $query = "INSERT INTO `shops`(`shop_url`, `access_token`) VALUES ('$shop_url','$response[access_token]')";
        
        $query_exec = $conn->query($query);
        if ($query_exec) {
            header("location:https://" . $shop_url . "/admin/apps");
        }
    } else {

        $update_token = "UPDATE shops SET `access_token`='$response[access_token]' WHERE  shop_url= '$shop_url'";
        

        $update_token_exec = $conn->query($update_token);
        header("location:https://" . $shop_url . "/admin/apps");
    }
} else {

    echo "Url Error H mAck missing";
}
