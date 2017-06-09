<?php

include_once '../config/init.php';
$general->adminLoggedOutProtect();

// ADD NEW PRODUCT
if (isset($_POST['newProductSubmit'])) {

  if(
    empty($_POST['newProductName']) ||
    empty($_POST['newProductDescription']) ||
    empty($_POST['newProductImageUrl']) ||
    empty($_POST['newProductPrice']) ||
    empty($_POST['newProductQuantity'])
  ) {

    $productErrors[] = 'All fields are required.<br>';

  } else if(
    !empty($_POST['newProductName']) &&
    !empty($_POST['newProductDescription']) &&
    !empty($_POST['newProductImageUrl']) &&
    !empty($_POST['newProductPrice']) &&
    !empty($_POST['newProductQuantity'])
  ) {

    $newProductImageUrl = $_POST['newProductImageUrl'];

    $newProductImageName = basename($newProductImageUrl);
    list($newProductImageNameText, $newProductImageNameExtension) = explode(".", $newProductImageName);
    $newProductImageName = $newProductImageNameText.time();
    $newProductImageName = $newProductImageName . "." . $newProductImageNameExtension;

    if(
      $newProductImageNameExtension == "jpg" or
      $newProductImageNameExtension == "jpeg" or
      $newProductImageNameExtension == "png" or
      $newProductImageNameExtension == "gif"
    ) {

      $newProductImageUpload = file_put_contents("../images/products/$newProductImageName",file_get_contents($newProductImageUrl));

    } else {

      $productErrors[] = 'Please only upload image files. Allowed extensions: .jpg, .jpeg, .png or .gif.<br>';

    }

    $newProductName = htmlentities($_POST['newProductName']);
    $newProductDescription = htmlentities($_POST['newProductDescription']);
    $newProductPrice = htmlentities($_POST['newProductPrice']);
    $newProductQuantity = htmlentities($_POST['newProductQuantity']);

    $products->addNewOwnProduct($newProductName, $newProductDescription, $newProductImageUrl, $newProductPrice, $newProductQuantity);
    $update->ownProductsToJSON();
    $update->ownProductsToXML();

    header('Location: home.php?product-added');
    exit();

  }
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Panel | eShop</title>
  <link rel="shortcut icon" href="../images/ico/favicon.ico">
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/main.css" rel="stylesheet">
</head>

<body class="eShop admin">
<?php include_once '../header.php'; ?>

<div class="container">
  <?php
  if (isset($_GET['product-added']) && empty($_GET['product-added'])) {
    echo "<div class='alert alert-success alertTop'>
              <strong>Yay, well done. </strong>A new product has been added successfully to your shop.
          </div>";
  }
  ?>
  <div class="row">
    <div role="tabpanel">

      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#adminHome" aria-controls="adminHome" role="tab" data-toggle="tab">General stats</a></li>
        <li role="presentation"><a href="#adminOrders" aria-controls="adminOrders" role="tab" data-toggle="tab">Orders placed to us</a></li>
        <li role="presentation"><a href="#adminOrders2" aria-controls="adminOrders2" role="tab" data-toggle="tab">Orders placed to partners</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="adminHome">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <div class="panel-title">Add a new product</div>
                    </div>
                    <div class="panel-body">
                      <form accept-charset="UTF-8" role="form" method="post">
                        <fieldset>
                          <div class="form-group">
                            <input type="text" name="newProductName" placeholder="name" class="form-control" autofocus/>
                          </div>
                          <div class="form-group">
                            <input type="text" name="newProductPrice" placeholder="price" class="form-control" />
                          </div>
                          <div class="form-group">
                            <input type="url" name="newProductImageUrl" placeholder="image url" class="form-control" />
                          </div>
                          <div class="form-group">
                            <textarea name="newProductDescription" placeholder="description" class="form-control" rows="2"></textarea>
                          </div>
                          <div class="form-group">
                            <input type="number" name="newProductQuantity" placeholder="quantity" class="form-control" />
                          </div>
                          <button class="btn btn-lg btn-primary btn-block" type="submit" name="newProductSubmit">Add new product</button>
                        </fieldset>
                      </form>
                    </div>
                    <?php
                    if(empty($productErrors) === false){
                      echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($productErrors) . '</div></div>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <div class="panel-title">Active users</div>
                    </div>
                    <div class="panel-body">

                      <table class="table">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>name</th>
                          <th>e-mail</th>
                        </tr>
                        </thead>
                        <tbody class="text-left">
                        <?php
                        $allUsers = $users->getAllUsers();

                        $n = 1;

                        foreach ($allUsers as $user) {
                          echo '
                            <tr>
                              <td>' . $n . '</td>
                              <td>' . $user['user_name'] . '</td>
                              <td>' . $user['user_email'] . '</td>
                            </tr>
                          ';
                          $n++;
                        }
                        ?>
                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <div class="panel-title">Active partners</div>
                    </div>
                    <div class="panel-body">
                      <table class="table">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>shop name</th>
                          <th>shop email</th>
                        </tr>
                        </thead>
                        <tbody class="text-left">
                        <?php
                        $allVendors = $vendors->getAllVendors();

                        $n = 1;

                        foreach ($allVendors as $vendor) {
                          echo '
                            <tr>
                              <td>' . $n . '</td>
                              <td>' . $vendor['vendor_name'] . '</td>
                              <td>' . $vendor['vendor_email'] . '</td>
                            </tr>
                          ';
                          $n++;
                        }
                        ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="adminOrders">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="panel-title">Orders placed to us</div>
                </div>
                <div class="panel-body">
                  <table class="table">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>placed</th>
                      <th>product</th>
                      <th>quantity</th>
                      <th>total</th>
                      <th>buyer</th>
                      <th>phone number</th>
                      <th>delivery address</th>
                    </tr>
                    </thead>
                    <tbody class="text-left">
                    <?php
                    $ourOrders = $orders->getAllOwnOrders();
                    $productNames = $orders->getProductNameInOrdersByProductId();

                    foreach ($ourOrders as $index => $order) {
                      echo '
                        <tr>
                          <td>' . $order['order_id'] . '</td>
                          <td>' . date('d M Y, H:i', $order['order_timestamp']) . '</td>
                          <td>' . $productNames[$index][1] . '</td>
                          <td>' . $order['order_product_quantity'] . '</td>
                          <td>DKK ' . $order['order_total'] . '</td>
                          <td>' . $order['order_email'] . '</td>
                          <td>' . $order['order_phone_number'] . '</td>
                          <td>' . $order['order_delivery_address'] . '</td>
                        </tr>
                      ';
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="adminOrders2">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="panel-title">Orders placed to partners</div>
                </div>
                <div class="panel-body">
                  <table class="table">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>placed</th>
                      <th>product</th>
                      <th>price</th>
                      <th>quantity</th>
                      <th>total for partner</th>
                      <th>commission</th>
                      <th>commission taken</th>
                    </tr>
                    </thead>
                    <tbody class="text-left">
                    <?php
                    $partnerOrders = $orders->getAllPartnerOrders();
                    $productInfo = $orders->getProductNameAndPriceInPartnerOrdersByProductId();

                    foreach ($partnerOrders as $index => $order) {
                      $productCommission = $order['order_product_quantity'] * $productInfo[$index][2] - $order['order_total'];
                      $commissionPercentage = ($productCommission * 100) / ($order['order_product_quantity'] * $productInfo[$index][2]);
                      echo '
                        <tr>
                          <td>' . $order['order_id'] . '</td>
                          <td>' . date('d M Y, H:i', $order['order_timestamp']) . '</td>
                          <td>' . $productInfo[$index][1] . '</td>
                          <td>DKK ' . $productInfo[$index][2] . '</td>
                          <td>' . $order['order_product_quantity'] . '</td>
                          <td>DKK ' . $order['order_total'] . '</td>
                          <td>' . number_format((float)$commissionPercentage, 0, '.', '') . '%</td>
                          <td>DKK ' . $productCommission . '</td>
                        </tr>
                      ';
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include_once '../footer.php';
ob_flush();

?>