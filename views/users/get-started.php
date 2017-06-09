<?php

include_once '../../config/init.php';
$general->userLoggedInProtect();
$general->vendorLoggedInProtect();

// LOGIN
if (isset($_POST['loginSubmit'])) {

  if (empty($_POST) === false) {

    $loginEmail = trim($_POST['loginEmail']);
    $loginPassword = trim($_POST['loginPassword']);

    if (empty($loginEmail) === true || empty($loginPassword) === true) {
      $loginErrors[] = 'Sorry, but we need both your e-mail and password.';
    } else if ($users->doesLoginEmailExist($loginEmail) === false) {
      $loginErrors[] = 'Oops, looks like that e-mail isn\'t registered.';
    } else if ($users->isLoginEmailConfirmed($loginEmail) === false) {
      $loginErrors[] = 'You need to activate your account. Please check your e-mail.';
    } else {

      $userLogin = $users->loginUser($loginEmail, $loginPassword);

      if ($userLogin === false) {

        $loginErrors[] = 'Oops, seems like that e-mail/password is invalid.';

      } else {

        $_SESSION['userId'] = $userLogin;

        header('Location: /views/users/home.php');
        exit();

      }
    }
  }
}

// REGISTRATION
if (isset($_POST['registerSubmit'])) {

  if(empty($_POST['registerEmail']) || empty($_POST['registerPassword']) || empty($_POST['registerName'])){

    $registerErrors[] = 'All fields are required.<br>';

  } else {

    if (filter_var($_POST['registerEmail'], FILTER_VALIDATE_EMAIL) === false) {
      $registerErrors[] = 'Please enter a valid e-mail address.<br>';
    } else if ($users->doesRegisterEmailExist($_POST['registerEmail']) === true) {
      $registerErrors[] = 'Darn, that e-mail address is already registered.<br>';
    }
    if (strlen($_POST['registerPassword']) < 6){
      $registerErrors[] = 'Your password must be at least 6 characters long.<br>';
    }
    if($_POST['registerPassword'] != $_POST['confirmRegisterPassword']){
      $registerErrors[] = 'Oops, looks like the passwords don\'t match.<br>';
    }

  }

  if(empty($registerErrors) === true){

    $name = htmlentities($_POST['registerName']);
    $email = htmlentities($_POST['registerEmail']);
    $password = $_POST['registerPassword'];

    $users->registerUser($name, $email, $password);
    header('Location: get-started.php?success');
    exit();

  }
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register | eShop</title>
  <link rel="shortcut icon" href="../../images/ico/favicon.ico">
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/font-awesome.min.css" rel="stylesheet">
  <link href="../../css/main.css" rel="stylesheet">
</head>

<body class="eShop users">

<?php include_once '../../header.php'; ?>

<div id="myAccount" class="container">
  <?php
  if (isset($_GET['success']) && empty($_GET['success'])) {
    echo "<div class='alert alert-success alertTop'>
              <strong>Yay, you're in. </strong>If the e-mail address you provided is valid, you should receive an activation link.
              Follow that link to activate your account and start shopping.
          </div>";
  }
  ?>
  <div class="row">
    <!-- login -->
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Returning customer?</h3>
        </div>
        <div class="panel-body">
          <form accept-charset="UTF-8" role="form" method="post">
            <fieldset>
              <div class="form-group">
                <label for="loginEmail" class="control-label">Your e-mail</label>
                <input type="email" id="loginEmail" name="loginEmail" class="form-control" autofocus
                       value="<?php if(isset($_POST['loginEmail'])) echo htmlentities($_POST['loginEmail']); ?>" />
              </div>
              <div class="form-group">
                <label for="loginPassword" class="control-label">Your password</label>
                <input type="password" id="loginPassword" name="loginPassword" class="form-control" />
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="loginSubmit">Sign In</button>
            </fieldset>
          </form>
        </div>
        <?php
        if(empty($loginErrors) === false){
          echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($loginErrors) . '</div></div>';
        }
        ?>
      </div>
    </div>
    <div class="col-md-2">
      <div class="or">
        OR
      </div>
    </div>
    <!-- register -->
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">New customer?</h3>
        </div>
        <div class="panel-body">
          <form accept-charset="UTF-8" role="form" method="post">
            <fieldset>
              <div class="form-group">
                <label for="registerName" class="control-label">Your name</label>
                <input type="text" id="registerName" name="registerName" placeholder="Full name" class="form-control"
                       value="<?php if(isset($_POST['registerName'])) echo htmlentities($_POST['registerName']); ?>" />
              </div>
              <div class="form-group">
                <label for="registerEmail" class="control-label">Your e-mail</label>
                <input type="email" id="registerEmail" name="registerEmail" placeholder="example@domain.com" class="form-control"
                       value="<?php if(isset($_POST['registerEmail'])) echo htmlentities($_POST['registerEmail']); ?>" />
              </div>
              <div class="form-group">
                <label for="registerPassword" class="control-label">Choose a password</label>
                <input type="password" id="registerPassword" name="registerPassword" placeholder="minimum 6 characters" class="form-control" />
              </div>
              <div class="form-group">
                <label for="confirmRegisterPassword" class="control-label">Verify password</label>
                <input type="password" id="confirmRegisterPassword" name="confirmRegisterPassword" placeholder="same as above" class="form-control" />
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="registerSubmit">Create Account</button>
            </fieldset>
          </form>
        </div>
        <?php
        if(empty($registerErrors) === false){
          echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($registerErrors) . '</div></div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php

include_once '../../footer.php';
ob_flush();

?>

