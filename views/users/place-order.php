<?php

include_once '../../config/init.php';
$general->userLoggedOutProtect();

$customerId= $user['user_id'];
$customerName = $user['user_name'];
$customerEmail = $user['user_email'];

if (isset($_POST['placeOrder'])) {

  if (
    empty($_POST['customerPhoneNumber']) ||
    empty($_POST['customerDeliveryAddress']) ||
    empty($_POST['customerProductQuantity'])
  ) {

    $errors[] = 'All fields are required.';

  }

  if(empty($errors) === true) {

    $productId = trim($_GET['productId']);
    $extProductId = trim($_GET['productExtId']);
    $vendorId = trim($_GET['vendorId']);
    $userId = trim($_GET['userId']);

    if ($vendorId == 28) {

      $productId = trim($_GET['productId']);
      $vendorId = trim($_GET['vendorId']);

      $customerPhoneNumber = htmlentities($_POST['customerPhoneNumber']);
      $customerDeliveryAddress = htmlentities($_POST['customerDeliveryAddress']);
      $customerProductQuantity = htmlentities($_POST['customerProductQuantity']);
      $customerProductPrice= htmlentities($_POST['productPriceFromLocalStorage']);
      $customerOrderTotal = $customerProductPrice * $customerProductQuantity;

      $placeOrder = $orders->addNewOrder($vendorId, $customerId, $productId, $customerProductQuantity, $customerOrderTotal, $customerDeliveryAddress, $customerEmail, $customerPhoneNumber);

      $orderConfirmationSubject = 'eShop - Order confirmation';
      $orderConfirmationBody = "Hi there,\r\n\r\nYour order has been placed and it is now being processed.\r\n\r\nThank you for shopping with us.\r\n\r\nThe eShop Team";

      mail($customerEmail, $orderConfirmationSubject, $orderConfirmationBody);

      header('Location: /eshop/views/users/home.php?order-placed') ;
      exit();

    } else {

      $vendorCommission = $vendors->getCommissionByVendorId($vendorId);

      $customerEmailAddress = htmlentities($_POST['customerEmailAddress']);
      $customerPhoneNumber = htmlentities($_POST['customerPhoneNumber']);
      $customerDeliveryAddress = htmlentities($_POST['customerDeliveryAddress']);
      $customerProductQuantity = htmlentities($_POST['customerProductQuantity']);
      $customerProductPrice= htmlentities($_POST['productPriceFromLocalStorage']);

      $customerOrderCommission = ($customerProductPrice * $customerProductQuantity) * $vendorCommission / 100;
      $customerOrderTotal = ($customerProductPrice * $customerProductQuantity) - $customerOrderCommission;

      $placeOrder = $orders->addNewOrder($vendorId, $customerId, $extProductId, $customerProductQuantity, $customerOrderTotal, $customerDeliveryAddress, $customerEmail, $customerPhoneNumber);

      header('Location: home.php?partner-order-placed');
      exit();

    }

  }
}

?>


  <!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Place Order | eShop</title>
    <link rel="shortcut icon" href="../../images/ico/favicon.ico">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/font-awesome.min.css" rel="stylesheet">
    <link href="../../css/main.css" rel="stylesheet">
  </head>

<body class="eShop order">
<?php include_once '../../header.php'; ?>

  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="panel-title">Please enter contact and delivery details</div>
          </div>
          <div class="panel-body">
            <form accept-charset="UTF-8" role="form" method="post" id="orderForm">
              <div class="form-group">
                <label for="customerEmailAddress" class="control-label">Your e-mail address</label>
                <input type="text" id="customerEmailAddress" name="customerEmailAddress"
                       class="form-control" placeholder="example@domain.com"
                       value="<?php echo $customerEmail; ?>" readonly/>
              </div>
              <div class="form-group">
                <label for="customerPhoneNumber" class="control-label">Your phone number</label>
                <input type="text" id="customerPhoneNumber" name="customerPhoneNumber"
                       class="form-control" placeholder="+45 50 50 30 30" autofocus
                       value="<?php if(isset($_POST['customerPhoneNumber'])) echo htmlentities($_POST['customerPhoneNumber']); ?>"/>
              </div>
              <div class="form-group">
                <label for="customerDeliveryAddress" class="control-label">Delivery address</label>
                <input type="text" id="customerDeliveryAddress" name="customerDeliveryAddress"
                       class="form-control" placeholder="Lygten 37, 2200 Copenhagen N"
                       value="<?php if(isset($_POST['customerDeliveryAddress'])) echo htmlentities($_POST['customerDeliveryAddress']); ?>"/>
              </div>
              <button type="submit" id="placeOrder" name="placeOrder" class='btn btn-primary btn-lg btn-block'>I'm ready. Place my order</button>
            </form>
          </div>
          <?php
          if(empty($errors) === false){
            echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($errors) . '</div></div>';
          }
          ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="panel panel-default text-center">
          <div class="panel-heading">
            <div class="panel-title">Your order details</div>
          </div>
          <div class="panel-body">
            <div id="order-details-image"></div>
            <h5><strong id="order-details-name"></strong></h5>
            <p id="order-details-description"></p>
            <span id="order-details-price" class="btn btn-default price"></span>
            <span><input type="number" form="orderForm" value="1" id="customerProductQuantity" name="customerProductQuantity" class="form-control" placeholder="10"/></span>
            <input type="hidden" form="orderForm" name="productPriceFromLocalStorage" class="productPriceFromLocalStorage"/>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php

include_once '../../footer.php';
ob_flush();

?>