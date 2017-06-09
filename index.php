<?php

include_once 'config/init.php';
$general->userLoggedInProtect();
$general->vendorLoggedInProtect();

// EDIT PRODUCT
if (isset($_POST['submitEditProduct'])) {

  if(
    empty($_POST['editProductName']) ||
    empty($_POST['editProductPrice']) ||
    empty($_POST['editProductImageUrl']) ||
    empty($_POST['editProductDescription']) ||
    empty($_POST['editProductQuantity'])
  ) {

    $errors[] = 'All fields are required.';

  }

  if (empty($errors) === true) {

    $editProductId = htmlentities($_POST['editProductId']);
    $editProductName = htmlentities($_POST['editProductName']);
    $editProductPrice = htmlentities($_POST['editProductPrice']);
    $editProductImageUrl = htmlentities($_POST['editProductImageUrl']);
    $editProductDescription = htmlentities($_POST['editProductDescription']);
    $editProductQuantity = htmlentities($_POST['editProductQuantity']);

    $products->editOwnProduct($editProductName, $editProductDescription, $editProductImageUrl, $editProductQuantity, $editProductPrice, $editProductId);
    header('Location: index.php?product-edited');
    exit();

  }
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Home | eShop</title>
  <link rel="shortcut icon" href="images/ico/favicon.ico">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
</head>

<body class="eShop home">
<?php include_once 'header.php'; ?>

<!-- Modal Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-md-9">
            <form accept-charset="UTF-8" role="form" method="post" id="editProductForm">
              <div class="form-group">
                <label for="editProductName" class="control-label">Product name</label>
                <input type="text" id="editProductName" name="editProductName" placeholder="name" class="form-control"/>
              </div>
              <div class="form-group">
                <label for="editProductPrice" class="control-label">Product price</label>
                <input type="text" id="editProductPrice" name="editProductPrice" placeholder="price" class="form-control" />
              </div>
              <div class="form-group">
                <label for="editProductImageUrl" class="control-label">Product image</label>
                <input type="url" id="editProductImageUrl" name="editProductImageUrl" placeholder="image url" class="form-control" />
              </div>
              <div class="form-group">
                <label for="editProductQuantity" class="control-label">Product quantity</label>
                <input type="text" id="editProductQuantity" name="editProductQuantity" placeholder="quantity" class="form-control" />
              </div>
              <div class="form-group">
                <label for="editProductDescription" class="control-label">Product description</label>
                <textarea id="editProductDescription" name="editProductDescription" placeholder="description" class="form-control" rows="3"></textarea>
              </div>
              <input type="hidden" id="editProductId" name="editProductId"/>
            </form>
          </div>
          <div class="col-md-3 text-right">
            <button type="submit" form="editProductForm" name="submitEditProduct" id="submitEditProduct" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Product -->
<div class='modal fade' id='deleteProductModal' tabindex='-1' role='dialog' aria-labelledby='deleteProductModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-body'>
        <div class='row'>
          <div class='col-md-9'>
            <div class='text'>Are you sure you want to remove this product from the shop?</div>
          </div>
          <div class='col-md-3 text-right'>
            <a href='#' class='btn btn-primary' id="remove-product">Yes, remove</a>
            <button type='button' class='btn btn-default' data-dismiss='modal'>No, cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <?php
  if (isset($_GET['order-placed']) && empty($_GET['order-placed'])) {
    echo "<div class='alert alert-success alertTop'>
              <strong>Boom, your order has been placed. </strong>If the e-mail address you provided is valid, you should receive a confirmation of your order.
              Thank you for shopping with us.
          </div>";
  }
  if (isset($_GET['partner-order-placed']) && empty($_GET['partner-order-placed'])) {
    echo "<div class='alert alert-success alertTop'>
              <strong>Your order has been created. </strong>Because you ordered a product sold by a partner, we have sent the order information to them and they will handle the processing.
          </div>";
  }
  if (isset($_GET['partner-edited']) && empty($_GET['partner-edited'])) {
    echo "<div class='alert alert-success alertTop'>
              You have edited a product successfully.
          </div>";
  }
  if(empty($errors) === false){
    echo '<div class="alert alert-danger alertTop">' . implode($errors) . '</div>';
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

            if($general->adminLoggedIn()) {
              echo '
                <div class="col-md-3">
                  <div class="thumbnail product">
                    <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                    <div class="caption text-center">
                      <h4 class="title">' . $product['product_name'] . '</h4>
                      <p class="description">' . $product['product_description'] . '</p>
                      <p class="controls">
                        <span class="btn btn-default price">#' . $product['product_price'] . '</span>
                      </p>
                      <a href="#" data-toggle="modal" data-target="#editProductModal" class="edit"
                      data-product-id="' . $product['product_id'] . '"
                      data-product-name="' . $product['product_name'] . '"
                      data-product-description="' . $product['product_description'] . '"
                      data-product-image="' . $product['product_image_url'] . '"
                      data-product-price="' . $product['product_price'] . '"
                      data-product-quantity="' . $product['product_quantity'] . '"><i class="fa fa-pencil fa-lg"></i></a>
                      <a href="#" data-toggle="modal" data-target="#deleteProductModal" data-remove-product="' . $product['product_id'] . '" class="remove"><i class="fa fa-times fa-lg"></i></a>
                    </div>
                  </div>
                </div>
              ';
            } else {
              echo '
                <div class="col-md-3">
                  <div class="thumbnail product">
                    <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                    <div class="caption text-center">
                      <h4 class="title">' . $product['product_name'] . '</h4>
                      <p class="description">' . $product['product_description'] . '</p>
                      <p class="controls">
                        <span class="btn btn-default price"># ' . $product['product_price'] . '</span>
                        <a href="/eshop/place-order.php?productId=' . $product['product_id'] . '&vendorId=' . $product['vendor_id'] . '&userId=0"
                         class="btn btn-primary review-order" role="button"
                        data-product-id="' . $product['product_id'] . '"
                        data-product-vendor-id="' . $product['vendor_id'] . '"
                        data-product-name="' . $product['product_name'] . '"
                        data-product-description="' . $product['product_description'] . '"
                        data-product-image="' . $product['product_image_url'] . '"
                        data-product-price="' . $product['product_price'] . '">Buy this</a>
                      </p>
                      <span class="badge">' . $product['product_quantity'] . '</span>
                    </div>
                  </div>
                </div>
              ';
            }
          };

          ?>

        </div>

        <div role="tabpanel" class="tab-pane fade" id="partnerProducts">

          <?php

          $vendorProducts = $products->getAllVendorProducts();

          foreach ($vendorProducts as $product) {

            if($general->adminLoggedIn()) {
              echo '
                <div class="col-md-3">
                  <div class="thumbnail product">
                    <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                    <div class="caption text-center">
                      <h4 class="title">' . $product['product_name'] . '</h4>
                      <p class="description">' . $product['product_description'] . '</p>
                      <p class="controls">
                        <span class="btn btn-default price">#' . $product['product_price'] . '</span>
                      </p>
                    </div>
                  </div>
                </div>
              ';
            } else {
              echo '
                <div class="col-md-3">
                  <div class="thumbnail product">
                    <div class="image" style="background: url(' . $product['product_image_url'] . ') no-repeat center; background-size:100%"></div>
                    <div class="caption text-center">
                      <h4 class="title">' . $product['product_name'] . '</h4>
                      <p class="description">' . $product['product_description'] . '</p>
                      <p class="controls">
                        <span class="btn btn-default price">#' . $product['product_price'] . '</span>
                        <a href="/eshop/place-order.php?productId=' . $product['product_id'] . '&productExtId=' . $product['ext_product_id'] . '&vendorId=' . $product['vendor_id'] . '&userId=0"
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
            }

          };

          ?>


        </div>
      </div>
    </div>
  </div>
</div>

<?php if(!$general->adminLoggedIn()) { ?>
<a href="views/vendors/get-started.php" id="vendorTease">
  Partner with us
</a>
<?php } ?>

<?php include_once 'footer.php'; ?>