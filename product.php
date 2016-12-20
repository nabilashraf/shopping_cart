<?php
// start session
session_start();
 
// include classes
include_once "config/database.php";
include_once "objects/product.php";
include_once "objects/product_image.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$product = new Product($db);
$product_image = new ProductImage($db);
 
// get ID of the product to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
 
// set the id as product id property
$product->id = $id;
 
// to read single record product
$product->readOne();
 
// set page title
$page_title = $product->name;
 
// include page header HTML
include_once 'layout_head.php';
 
// content will be here
 
// include page footer HTML
include_once 'layout_foot.php';
?>