<?php

include_once '../../config/init.php';
$general->userLoggedOutProtect();

$customerId= $user['user_id'];
$customerName = $user['user_name'];

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Home | eShop</title>
  <link rel="shortcut icon" href="../../images/ico/favicon.ico">
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/font-awesome.min.css" rel="stylesheet">
  <link href="../../css/main.css" rel="stylesheet">
</head>

<body class="eShop home">
<?php include_once '../../header.php'; ?>

  <div class="container">
    <?php
    if (isset($_GET['order-placed']) && empty($_GET['order-placed'])) {
      echo "<div class='alert alert-success alertTop'>
              <strong>Boom, your order has been placed. </strong>You should receive a confirmation of your order on your e-mail.
              Thank you for shopping with us.
          </div>";
    }
    if (isset($_GET['partner-order-placed']) && empty($_GET['partner-order-placed'])) {
      echo "<div class='alert alert-success alertTop'>
              <strong>Your order has been created. </strong>Because you ordered a product sold by a partner, we have sent the order information to them and they will handle the processing.
          </div>";
    }
    ?>
    <div class="row">
      <div role="tabpanel">

        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#ownProducts" aria-controls="ownProducts" role="tab" data-toggle="tab">Our products</a></li>
          <li role="presentation"><a href="#partnerProducts" aria-controls="partnerProducts" role="tab" data-toggle="tab">Products from partners</a></li>
        </ul>

        <div class="tab-content">

          <div role="tabpanel" class="tab-pane fade in active" id="ownProducts">

            <?php

            $ownProducts = $products->getAllOwnProducts();

            foreach ($ownProducts as $product) {
              echo '
                <div class="col-md-3">
                  <div class="thumbnail product">
                    <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                    <div class="caption text-center">
                      <h4 class="title">' . $product['product_name'] . '</h4>
                      <p class="description">' . $product['product_description'] . '</p>
                      <p class="controls">
                        <span class="btn btn-default price">DKK ' . $product['product_price'] . '</span>
                        <a href="place-order.php?productId=' . $product['product_id'] . '&vendorId=' . $product['vendor_id'] . '&userId=' . $customerId . '"
                         class="btn btn-primary review-order" role="button"
                        data-product-id="' . $product['product_id'] . '"
                        data-product-vendor-id="' . $product['vendor_id'] . '"
                        data-product-name="' . $product['product_name'] . '"
                        data-product-description="' . $product['product_description'] . '"
                        data-product-image="' . $product['product_image_url'] . '"
                        data-product-price="' . $product['product_price'] . '">Buy this</a>
                      </p>
                      <span class="badge">' . $product['product_quantity'] . ' stk.</span>
                    </div>
                  </div>
                </div>
              ';
            };

            ?>

          </div>

          <div role="tabpanel" class="tab-pane fade" id="partnerProducts">

            <?php

            $vendorProducts = $products->getAllVendorProducts();

            foreach ($vendorProducts as $product) {
              echo '
              <div class="col-md-3">
                <div class="thumbnail product">
                  <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                  <div class="caption text-center">
                    <h4 class="title">' . $product['product_name'] . '</h4>
                    <p class="description">' . $product['product_description'] . '</p>
                    <p class="controls">
                      <span class="btn btn-default price">DKK ' . $product['product_price'] . '</span>
                      <a href="place-order.php?productId=' . $product['product_id'] . '&productExtId=' . $product['ext_product_id'] . '&vendorId=' . $product['vendor_id'] . '&userId=0"
                       class="btn btn-primary review-order" role="button"
                      data-product-id="' . $product['product_id'] . '"
                      data-product-vendor-id="' . $product['vendor_id'] . '"
                      data-product-name="' . $product['product_name'] . '"
                      data-product-description="' . $product['product_description'] . '"
                      data-product-image="' . $product['product_image_url'] . '"
                      data-product-price="' . $product['product_price'] . '">Buy this</a>
                    </p>
                  </div>
                </div>
              </div>
            ';
            };

            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php

include_once '../../footer.php';
ob_flush();

?>