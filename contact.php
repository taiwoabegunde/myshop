<?php

include_once 'config/init.php';

if (isset($_POST['contactUs'])) {

  if (
    empty($_POST['contactName']) ||
    empty($_POST['contactEmail']) ||
    empty($_POST['contactSubject']) ||
    empty($_POST['contactMessage'])
  ) {

    $errors[] = 'All fields are required.';

  } else {

    $contactName = htmlentities($_POST['contactName']);
    $contactEmail = htmlentities($_POST['contactEmail']);
    $contactSubject = htmlentities($_POST['contactSubject']);
    $contactMessage = htmlentities($_POST['contactMessage']);

    $mailSubject = 'eShop - ' . $contactName . ' has written you a message';
    $mailMessage = "From: " . $contactName . "\r\nE-mail: " . $contactEmail . "\r\n\r\nSubject: " . $contactSubject . "\r\n\r\nMessage: " . $contactMessage . "";

    mail('andrei.horodinca@gmail.com', $mailSubject, $mailMessage);

    header('Location: contact.php?message-sent');
    exit();

  }
}

?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Get in touch | eShop</title>
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script>
      function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
          center: new google.maps.LatLng(55.6761, 12.5683),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(mapCanvas, mapOptions)
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>

<body class="eShop contact">
<?php include_once 'header.php'; ?>

  <div class="container">
    <?php
    if (isset($_GET['message-sent']) && empty($_GET['message-sent'])) {
      echo "<div class='alert alert-success alertTop'>
              <strong>Your message has been sent. </strong>We will get back to you as soon as possible. ;)
          </div>";
    }
    ?>
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="panel-title">Get in touch</div>
          </div>
          <div class="panel-body">
            <form accept-charset="UTF-8" role='form' method="post">
              <div class="form-group">
                <label for="contactName" class="control-label">Your name</label>
                <?php if($general->userLoggedIn()) { ?>
                <input type="text" id="contactName" name="contactName"
                       class="form-control" placeholder="Full name" value="<?php echo $user['user_name']; ?>" readonly/>
                <?php } else if ($general->vendorLoggedIn()) { ?>
                <input type="text" id="contactName" name="contactName"
                       class="form-control" placeholder="Full name" value="<?php echo $vendor['vendor_name']; ?>" readonly/>
                <?php } else { ?>
                <input type="text" id="contactName" name="contactName"
                       class="form-control" placeholder="Full name"/>
                <?php } ?>
              </div>
              <div class="form-group">
                <label for="contactEmail" class="control-label">Your e-mail address</label>
                <?php if($general->userLoggedIn()) { ?>
                  <input type="email" id="contactEmail" name="contactEmail"
                         class="form-control" placeholder="example@domain.com" value="<?php echo $user['user_email']; ?>" readonly/>
                <?php } else if ($general->vendorLoggedIn()) { ?>
                  <input type="email" id="contactEmail" name="contactEmail"
                         class="form-control" placeholder="example@domain.com" value="<?php echo $vendor['vendor_email']; ?>" readonly/>
                <?php } else { ?>
                  <input type="email" id="contactEmail" name="contactEmail"
                         class="form-control" placeholder="example@domain.com"/>
                <?php } ?>
              </div>
              <div class="form-group">
                <label for="contactSubject" class="control-label">Subject</label>
                <input type="text" id="contactSubject" name="contactSubject"
                       class="form-control" placeholder="Hello." autofocus
                       value="<?php if(isset($_POST['contactSubject'])) echo htmlentities($_POST['contactSubject']); ?>"/>
              </div>
              <div class="form-group">
                <label for="contactMessage" class="control-label">Your message</label>
                <textarea id="contactMessage" name="contactMessage" rows="3"
                       class="form-control" placeholder="Keep up the good work!"
                       value="<?php if(isset($_POST['contactMessage'])) echo htmlentities($_POST['contactMessage']); ?>"></textarea>
              </div>
              <button type="submit" id="contactUs" name="contactUs" class='btn btn-primary btn-lg btn-block'>Send my message</button>
            </form>
          </div>
          <?php
          if(empty($errors) === false){
            echo '<div class="panel-footer"><div class="alert alert-danger">' . implode($errors) . '</div></div>';
          }
          ?>
        </div>
      </div>
      <div class="col-md-6">
        <div id="map-canvas">
          <!--The Google map goes here-->
        </div>
      </div>
    </div>
  </div>

<?php

include_once 'footer.php';
ob_flush();

?>