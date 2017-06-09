<?php

include_once '../config/init.php';
$general->adminLoggedInProtect();

// LOGIN ADMIN
if (isset($_POST['adminSubmit'])) {

  if (empty($_POST) === false) {

    $adminUsername = trim($_POST['adminUsername']);
    $adminPassword = trim($_POST['adminPassword']);

    if (empty($adminUsername) === true || empty($adminPassword) === true) {
      $adminErrors[] = 'Sorry, but we need both your username and password.';
    } else if ($admin->doesAdminUsernameExist($adminUsername) === false) {
      $adminErrors[] = 'Oops, looks like that username does not belong to an admin.';
    } else {

      $adminLogin = $admin->loginAdmin($adminUsername, $adminPassword);

      if ($adminLogin === false) {

        $adminErrors[] = 'Oops, seems like that username/password is invalid.';

      } else {

        $_SESSION['adminId'] = $adminLogin;

        header('Location: /eshop/admin/home.php');
        exit();

      }
    }
  }
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login | eShop</title>
  <link rel="shortcut icon" href="../images/ico/favicon.ico">
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/main.css" rel="stylesheet">
</head>

<body class="eShop admin">
<?php include_once '../header.php'; ?>

<div class="container">
  <div class="row">
    <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title">Enter admin credentials</div>
        </div>
        <div class="panel-body">
          <form accept-charset="UTF-8" role="form" method="post">
            <fieldset>
              <div class="form-group">
                <input type="text" name="adminUsername" placeholder="admin username" class="form-control" autofocus
                       value="<?php if(isset($_POST['adminUsername'])) echo htmlentities($_POST['adminUsername']); ?>"/>
              </div>
              <div class="form-group">
                <input type="password" name="adminPassword" placeholder="admin password" class="form-control" />
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="adminSubmit">Proceed to admin panel</button>
            </fieldset>
          </form>
        </div>
        <?php
        if(empty($adminErrors) === false){
          echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($adminErrors) . '</div></div>';
        }
        ?>
      </div>
    </div>
    <div class="col-md-3">
      <div class="panel panel-default text-center">
        <div class="panel-heading">
          <div class="panel-title">Welcome, admin!</div>
        </div>
        <div class="panel-body">
          Sign in to administer your eShop.
          <br><br>
          <i class="fa fa-database fa-2x"></i>
          <br><br>
          Add products, administer partners and customers are awaiting on the other side.
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include_once '../footer.php';
ob_flush();

?>