<?php
include_once("./includes/db_config.php");
include_once("./includes/shopify.php");

$shopify = new Shopify();
$parameters = $_GET;

$query = "SELECT * FROM `shops` WHERE shop_url='$parameters[shop]' LIMIT 1";

$query_exec = $conn->query($query);

if ($query_exec->num_rows < 1) {

    header("location: install.php?shop=" . $_GET['shop']);
    exit();
}
if ($query_exec->num_rows > 0) {

    $store_data = $query_exec->fetch_assoc();

    echo "<h1> congratulation App Is Installed Succcesfully </h1>";



}

$shopify->set_url($parameters['shop']);
$shopify->set_token($store_data['access_token']);

echo $shopify->get_url();
echo $shopify->get_token();

$products = $shopify->rest_api("/admin/api/2021-04/products.json", array(), "GET");


$products = json_decode($products['body'], true);
echo "<pre>";
echo print_r($products);
echo "<pre>";