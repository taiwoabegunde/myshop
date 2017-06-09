<?php

session_start();

include_once 'classes/Admin.php';
include_once 'classes/Bcrypt.php';
include_once 'classes/General.php';
include_once 'classes/Order.php';
include_once 'classes/Product.php';
include_once 'classes/Search.php';
include_once 'classes/Update.php';
include_once 'classes/User.php';
include_once 'classes/Vendor.php';
include_once 'connect/connect.php';

/**
 * Construct global objects for our classes.
 */
$admin = new Admin($db);
$bcrypt = new Bcrypt();
$general = new General();
$orders = new Order($db);
$products = new Product($db);
$search = new Search($db);
$update = new Update($db);
$users = new User($db);
$vendors = new Vendor($db);

/**
 * We check if a user is logged in and return all information for the user.
 */
if ($general->userLoggedIn() === true)  {
  $userId = $_SESSION['userId'];
  $user = $users->userData($userId);
}

/**
 * We check if a partner is logged in and return all information for the partner.
 */
if ($general->vendorLoggedIn() === true)  {
  $vendorId = $_SESSION['vendorId'];
  $vendor = $vendors->vendorData($vendorId);
}

$errors = array();

ob_start();

?>