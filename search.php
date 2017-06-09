<?php

include_once 'config/init.php';

if($general->userLoggedIn()) {
  $customerId= $user['user_id'];
} else {
  $customerId = '0';
}

?>


<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Search Results | eShop</title>
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
  </head>

<body class="eShop searchResults">
<?php include_once 'header.php'; ?>

  <div class="container">
    <div class="row">
      <?php
      $minLength = 3;
      $q = $_GET['q'];

      if(strlen($q) >= $minLength) {

        $q = htmlspecialchars($q);

        $searchResults = $search->searchProducts($q);

        if (!empty($searchResults)) {

          echo '<div class="search-title">This is what we could find for: <b>' . $_GET['q'] . '</b></div>';

          foreach ($searchResults as $result) {
            echo '
              <div class="col-md-3">
                <div class="thumbnail product">
                  <div class="image" style="background: url(' . $result['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                  <div class="caption text-center">
                    <h4 class="title">' . $result['product_name'] . '</h4>
                    <p class="description">' . $result['product_description'] . '</p>
                    <p class="controls">
                      <span class="btn btn-default price"># ' . $result['product_price'] . '</span>
                      <a href="/place-order.php?productId=' . $result['product_id'] . '&vendorId=' . $result['vendor_id'] . '&userId=' . $customerId . '"
                       class="btn btn-primary review-order" role="button"
                      data-product-id="' . $result['product_id'] . '"
                      data-product-vendor-id="' . $result['vendor_id'] . '"
                      data-product-name="' . $result['product_name'] . '"
                      data-product-description="' . $result['product_description'] . '"
                      data-product-image="' . $result['product_image_url'] . '"
                      data-product-price="' . $result['product_price'] . '">Buy this</a>
                    </p>
                  </div>
                </div>
              </div>
            ';
          }

        } else {
          echo '<div class="search-title">We couldn\'t find any products matching: <b>' . $_GET['q'] . '</b></div>';
        }

      } else {
        echo '<div class="search-title">Your search query needs to be at least <b>' . $minLength . ' </b>characters long.</em></div>';
      }
      ?>
    </div>
  </div>

<?php

include_once 'footer.php';
ob_flush();

?>