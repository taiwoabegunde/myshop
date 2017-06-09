<?php

include_once '../../config/init.php';
$general->userLoggedInProtect();
$general->vendorLoggedInProtect();

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Activate | eShop</title>
  <link rel="shortcut icon" href="../../images/ico/favicon.ico">
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/font-awesome.min.css" rel="stylesheet">
  <link href="../../css/main.css" rel="stylesheet">
</head>

<body class="eShop activate">
<?php include_once '../../header.php'; ?>

<div class="container">
  <div class="row">
    <div class="col-md-9">

      <?php
      if (isset($_GET['success']) === true && empty ($_GET['success']) === true) {
      ?>

      <div class="panel panel-default bg-success">
        <div class="panel-heading">
          <div class="panel-title">Congratulations!</div>
        </div>
        <div class="panel-body">
          <strong class="text-success">Your account has been successfully activated.</strong>
          <br><br>
          Now you can start shopping. But first, please sign in.
        </div>
        <div class="panel-footer">
          <a href="get-started.php" class="btn btn-primary btn-lg">Sign In to Your Account</a>
        </div>
      </div>

      <?php

      } else if (isset ($_GET['email'], $_GET['code']) === true) {

        $email = trim($_GET['email']);
        $code	= trim($_GET['code']);

        if ($users->doesRegisterEmailExist($email) === false) {
          $errors[] = 'Sorry, we couldn\'t find that e-mail address.';
        } else if ($users->activateUser($email, $code) === false) {
          $errors[] = 'Sorry, we couldn\'t activate your account.';
        }

        if (empty($errors) === false ) {

          echo '
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Something went wrong</div>
              </div>
              <div class="panel-body">
                <strong class="text-danger">' . implode($errors) . '</strong>
              </div>
            </div>
          ';

        } else {
          header('Location: activate.php?success');
          exit();
        }

      } else {
        header('Location: /index.php');
        exit();
      }

      ?>

    </div>

    <div class="col-md-3">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <div class="panel-title">Become a partner</div>
        </div>
        <div class="panel-body">
          Sign up as a partner shop and feature your own products on our website.
          <br><br>
          <i class="fa fa-star fa-2x"></i>
          <br><br>
          We offer low commision, 24/7 support and permanent backup of your products and orders.
        </div>
        <div class="panel-footer">
          <a href="../vendors/get-started.php" class="btn btn-primary btn-lg btn-block">Get Started Now</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include_once '../../footer.php';
ob_flush();

?>