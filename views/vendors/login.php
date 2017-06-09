<?php

include_once '../../config/init.php';
$general->vendorLoggedInProtect();
$general->userLoggedInProtect();

// GENERATE KEY FOR LOGIN
if (isset($_POST['vendorSendKeySubmit'])) {

  if(empty($_POST['vendorLoginEmail'])) {

    $vendorLoginErrors[] = 'You need to enter the e-mail address you signed up with.';

  } else {

    if (filter_var($_POST['vendorLoginEmail'], FILTER_VALIDATE_EMAIL) === false) {
      $vendorLoginErrors[] = 'Please enter a valid e-mail address.';
    } else if ($vendors->doesVendorLoginEmailExist($_POST['vendorLoginEmail']) === false) {
      $vendorLoginErrors[] = 'Oops, looks like that e-mail address isn\'t registered.';
    } else if ($vendors->isVendorLoginEmailConfirmed($_POST['vendorLoginEmail']) === false) {
      $vendorLoginErrors[] = 'Oops, looks like that account hasn\'t been approved yet. Please contact us.';
    }

  }

  if(empty($vendorLoginErrors) === true) {

    $vendorLoginEmail = htmlentities($_POST['vendorLoginEmail']);
    $vendors->generateVendorLoginKey($vendorLoginEmail);

    header('Location: login.php?key-sent&email=' . $vendorLoginEmail . '');
    exit();
  }
}
// AND LOGIN
if (isset($_POST['vendorLoginSubmit'])) {

  if (empty($_POST) === false) {

    $loginEmail = $_POST['vendorLoginEmail'];
    $loginKey = $_POST['vendorLoginKey'];

    $vendorLogin = $vendors->verifyVendorLoginKey($loginEmail, $loginKey);

    if ($vendorLogin === false) {

      $vendorLoginErrors[] = 'Oops, looks like the login key you entered is incorrect.';

    } else {

      $_SESSION['vendorId'] = $vendorLogin;

      header('Location: /views/vendors/home.php');
      exit();

    }
  }

  }

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Partner Login | eShop</title>
  <link rel="shortcut icon" href="../../images/ico/favicon.ico">
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/font-awesome.min.css" rel="stylesheet">
  <link href="../../css/main.css" rel="stylesheet">
</head>

<body class="eShop partners">
<?php include_once '../../header.php'; ?>

<div class="container">
  <div class="row">
    <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title">Login to your partner account</div>
        </div>
        <div class="panel-body">

          <?php

          if (isset($_GET['key-sent']) === false && empty ($_GET['key-sent']) === true) { ?>

          <form accept-charset="UTF-8" role="form" method="post">
            <div class="form-group">
              <label for="vendorLoginEmail" class="control-label">Your e-mail</label>
              <input type="email" class="form-control" id="vendorLoginEmail" name="vendorLoginEmail" autofocus/>
            </div>
            <div class="help-block">
              To login as a partner, you need to go through the secure verification login process.
            </div>
            <div class="help-block" style="margin-bottom: 20px">
              A unique key will be generated and e-mailed to you. Use that key when prompted to login to your account.
            </div>
            <input type="submit" name="vendorSendKeySubmit" value="Send me the login key" class="btn btn-primary btn-block btn-lg"/>
          </form>

          <?php } else if (isset($_GET['key-sent']) === true) { ?>

          <div class='alert alert-success'><b>Your login key has been sent.</b> Check your e-mail, copy the key and paste it in the field below to login to your account.</div>
          <br>
          <form accept-charset='UTF-8' role='form' method='post'>
            <div class='form-group'>
              <label for='vendorLoginKey' class='control-label'>Login Key</label>
              <input type='hidden' name="vendorLoginEmail" value="<?php if (isset($_GET['email']) === true) {echo ($_GET['email']);}  ?>">
              <input type='text' id='vendorLoginKey' class='form-control' name='vendorLoginKey' autofocus/>
            </div>
            <input type='submit' name='vendorLoginSubmit' value='Login to your account' class='btn btn-primary btn-block btn-lg'/>
          </form>

          <?php } ?>

        </div>

        <?php

        if(empty($vendorLoginErrors) === false) {
          echo "<div class='panel-footer'><div class='alert alert-danger'>" . implode($vendorLoginErrors) . "</div></div>";
        }

        ?>
      </div>
    </div>
    <div class="col-md-3">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <div class="panel-title">Not a partner yet?</div>
        </div>
        <div class="panel-body">
          You have an online shop and want to feature and sell your products
          on our website?
          <br/><br/>
          Then sign up as a partner of eShop and benefit.
          We offer great services at low cost.
        </div>
        <div class="panel-footer">
          <a href="/views/vendors/get-started.php" class="btn btn-default btn-lg btn-block">Get Started Now</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include_once '../../footer.php';
ob_flush();

?>