<?php

include_once 'config/init.php';
$general->userLoggedInProtect();
$general->vendorLoggedInProtect();

if (isset($_POST['placeGuestOrder'])) {

  if (
    empty($_POST['guestCustomerEmailAddress']) ||
    empty($_POST['guestCustomerPhoneNumber']) ||
    empty($_POST['guestCustomerDeliveryAddress']) ||
    empty($_POST['guestCustomerProductQuantity'])
  ) {

    $errors[] = 'All fields are required.';

  } else {

    if (filter_var($_POST['guestCustomerEmailAddress'], FILTER_VALIDATE_EMAIL) === false) {
      $errors[] = 'Please enter a valid e-mail address.';
    } else if ($_POST['guestCustomerProductQuantity'] === 0) {
      $errors[] = 'The quantity of the product cannot be 0.';
    }
  }

  if(empty($errors) === true) {

    $productId = trim($_GET['productId']);
    $extProductId = trim($_GET['productExtId']);
    $vendorId = trim($_GET['vendorId']);
    $userId = trim($_GET['userId']);

    if ($vendorId == 28) {

      $guestCustomerEmailAddress = htmlentities($_POST['guestCustomerEmailAddress']);
      $guestCustomerPhoneNumber = htmlentities($_POST['guestCustomerPhoneNumber']);
      $guestCustomerDeliveryAddress = htmlentities($_POST['guestCustomerDeliveryAddress']);
      $guestCustomerProductQuantity = htmlentities($_POST['guestCustomerProductQuantity']);
      $guestCustomerProductPrice= htmlentities($_POST['productPriceFromLocalStorage']);
      $guestCustomerOrderTotal = $guestCustomerProductPrice * $guestCustomerProductQuantity;

      $placeGuestOrder = $orders->addNewOrder($vendorId, $userId, $productId, $guestCustomerProductQuantity, $guestCustomerOrderTotal, $guestCustomerDeliveryAddress, $guestCustomerEmailAddress, $guestCustomerPhoneNumber);

      $orderConfirmationSubject = 'eShop - Order confirmation';
      $orderConfirmationBody = "Hi there,\r\n\r\nYour order has been placed and it is now being processed.\r\n\r\nThank you for shopping with us.\r\n\r\nThe eShop Team";

      mail($guestCustomerEmailAddress, $orderConfirmationSubject, $orderConfirmationBody);

      header('Location: /eshop/index.php?order-placed');
      exit();

    } else {

      $vendorCommission = $vendors->getCommissionByVendorId($vendorId);

      $guestCustomerEmailAddress = htmlentities($_POST['guestCustomerEmailAddress']);
      $guestCustomerPhoneNumber = htmlentities($_POST['guestCustomerPhoneNumber']);
      $guestCustomerDeliveryAddress = htmlentities($_POST['guestCustomerDeliveryAddress']);
      $guestCustomerProductQuantity = htmlentities($_POST['guestCustomerProductQuantity']);
      $guestCustomerProductPrice= htmlentities($_POST['productPriceFromLocalStorage']);

      $guestCustomerOrderCommission = ($guestCustomerProductPrice * $guestCustomerProductQuantity) * $vendorCommission / 100;
      $guestCustomerOrderTotal = ($guestCustomerProductPrice * $guestCustomerProductQuantity) - $guestCustomerOrderCommission;

      $placeGuestOrderToPartner = $orders->addNewOrder($vendorId, $userId, $extProductId, $guestCustomerProductQuantity, $guestCustomerOrderTotal, $guestCustomerDeliveryAddress, $guestCustomerEmailAddress, $guestCustomerPhoneNumber);

      header('Location: /eshop/index.php?partner-order-placed');
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
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
  </head>

<body class="eShop order">
<?php include_once 'header.php'; ?>

  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="panel-title">Please enter contact and delivery details</div>
          </div>
          <div class="panel-body">
            <form accept-charset="UTF-8" role="form" method="post" id="guestOrderForm">
              <div class="form-group">
                <label for="guestCustomerEmailAddress" class="control-label">Your e-mail address</label>
                <input type="email" id="guestCustomerEmailAddress" name="guestCustomerEmailAddress"
                       class="form-control" placeholder="example@domain.com" autofocus
                       value="<?php if(isset($_POST['guestCustomerEmailAddress'])) echo htmlentities($_POST['guestCustomerEmailAddress']); ?>"/>
              </div>
              <div class="form-group">
                <label for="guestCustomerPhoneNumber" class="control-label">Your phone number</label>
                <input type="text" id="guestCustomerPhoneNumber" name="guestCustomerPhoneNumber"
                       class="form-control" placeholder="+2348058745033"
                       value="<?php if(isset($_POST['guestCustomerPhoneNumber'])) echo htmlentities($_POST['guestCustomerPhoneNumber']); ?>"/>
              </div>
              <div class="form-group">
                <label for="guestCustomerDeliveryAddress" class="control-label">Delivery address</label>
                <input type="text" id="guestCustomerDeliveryAddress" name="guestCustomerDeliveryAddress"
                       class="form-control" placeholder="Plot 4, Osolo way, Isolo, Lagos."
                       value="<?php if(isset($_POST['guestCustomerDeliveryAddress'])) echo htmlentities($_POST['guestCustomerDeliveryAddress']); ?>"/>
              </div>
              <button type="submit" id="placeGuestOrder" name="placeGuestOrder" class='btn btn-primary btn-lg btn-block'>I'm ready. Place my order</button>
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
            <span><input type="number" form="guestOrderForm" value="1" id="guestCustomerProductQuantity" name="guestCustomerProductQuantity" class="form-control" placeholder="10"/></span>
            <input type="hidden" form="guestOrderForm" name="productPriceFromLocalStorage" class="productPriceFromLocalStorage"/>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php

include_once 'footer.php';
ob_flush();

?>