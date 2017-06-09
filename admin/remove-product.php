<?php

include_once '../config/init.php';
$general->adminLoggedOutProtect();

if (isset($_GET['id']) === true) {

  $productToRemove = trim($_GET['id']);

  $removeProduct = $products->removeProduct($productToRemove);

  if ($removeProduct === false) {

    $errors[] = 'Sorry, but this product could not be removed from the shop.';

  } else {

    $update->ownProductsToJSON();
    $update->ownProductsToXML();

    header('Location: /index.php');
    exit();

  }

}