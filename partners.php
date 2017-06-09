<?php

include_once 'config/init.php';

// UPLOAD API PATH
if (isset($_POST['apiPathSubmit'])) {

  if(empty($_POST['apiPath'])) {

    $errors[] = 'You need to enter the path to your API (JSON or XML)';

  } else {

    // Check if path to API is .json or .xml
    if ( substr($_POST['apiPath'],-5) == ".json" || substr($_POST['apiPath'],-4) == ".xml" ) {

      $apiPath = htmlentities($_POST['apiPath']);
      $vendors->uploadPathToApi($apiPath, $vendor['vendor_id']);
      $update->vendorProductsToDb($vendor['vendor_id']);

      header('Location: home.php?api-updated');
      exit();

    } else {
      $errors[] = 'It looks like the path to your API is not JSON or XML.';
    }
  }
}

?>

  <!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Partners | eShop</title>
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
  </head>

<body class="eShop partners">
<?php include_once 'header.php'; ?>

<div class="container">
  <div class="row">
    <?php
    $allVendors = $vendors->getAllVendors();

    foreach ($allVendors as $vendor) {
      echo '
        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-body text-center partner">
              ' . $vendor['vendor_name'] . '
            </div>
          </div>
        </div>
      ';
    }
    ?>
  </div>
</div>

<?php

include_once 'footer.php';
ob_flush();

?>