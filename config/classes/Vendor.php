<?php

class Vendor {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function creating a new partner account in the system.
   *
   * @param vendorRegisterName
   * @param vendorRegisterEmail
   * @param vendorRegisterCommission
   *
   */
  public function registerVendor($vendorRegisterName, $vendorRegisterEmail, $vendorRegisterCommission) {

    $vendorActivationCode = $vendorActivationCode = uniqid(true); // We generate a unique activation code

    // We notify admin that a new partner signed up
    // Admin needs to approve the partner before he can log in
    $adminEmail = 'andrei.horodinca@gmail.com';
    $emailToAdminSubject = 'eShop - A new partner requires activation';
    $emailToAdminBody = "Hello admin,\r\n\r\nA shop owner has signed up as a partner on eShop. Follow the link below to approve the partnership:\r\n\r\nhttp://eshop.andreihorodinca.dk/views/vendors/activate.php?email=$vendorRegisterEmail&code=$vendorActivationCode\r\n\r\nStay classy!";

    // We notify the partner that his account is pending approval by admin
    $emailToVendorSubject = 'eShop - Thank your for signing up as a partner';
    $emailToVendorBody = "Hi there,\r\n\r\nThank you for signing up to become a partner of eShop.\r\n\r\nYour request is now pending approval. Once your account has been approved by an administrator, you will be notified on your email.\r\n\r\nThe eShop Team";

    $query = $this->db->prepare("INSERT INTO `vendors` (`vendor_name`, `vendor_email`, `vendor_commission`, `vendor_code`) VALUES (?, ?, ?, ?)");

    $query->bindValue(1, $vendorRegisterName);
    $query->bindValue(2, $vendorRegisterEmail);
    $query->bindValue(3, $vendorRegisterCommission);
    $query->bindValue(4, $vendorActivationCode);

    try {
      $query->execute();

      mail($adminEmail, $emailToAdminSubject, $emailToAdminBody); // We mail admin
      mail($vendorRegisterEmail, $emailToVendorSubject, $emailToVendorBody); // We mail partner

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function checking if the partner is already registered.
   *
   * @param vendorRegisterEmail
   *
   */
  public function doesVendorRegisterEmailExist($vendorRegisterEmail) {

    $query = $this->db->prepare("SELECT COUNT(`vendor_id`) FROM `vendors` WHERE `vendor_email`= ?");
    $query->bindValue(1, $vendorRegisterEmail);

    try {
      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1) {
        return true;
      } else {
        return false;
      }

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function approving and activating a new partner account.
   *
   * @param vendorRegisterEmail
   * @param vendorActivationCode
   *
   */
  public function activateVendor($vendorRegisterEmail, $vendorActivationCode) {

    $query = $this->db->prepare("SELECT COUNT(`vendor_id`) FROM `vendors` WHERE `vendor_email` = ? AND `vendor_code` = ? AND `vendor_confirmed` = ?");

    $query->bindValue(1, $vendorRegisterEmail);
    $query->bindValue(2, $vendorActivationCode);
    $query->bindValue(3, 0);

    // We e-mail the key to the partner trying to log in
    $emailToVendorSubject = 'eShop - Your account has been approved';
    $emailToVendorBody = "Hi there,\r\n\r\nYour partnership request has been approved and your account is now activated.\r\n\r\nYou can go ahead and login here:\r\n\r\nhttp://eshop.andreihorodinca.dk/views/vendors/login.php\r\n\r\nIt's great to have you on board. :)\r\n\r\nThe eShop Team";


    try {
      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1) {

        $query_2 = $this->db->prepare("UPDATE `vendors` SET `vendor_confirmed` = ? WHERE `vendor_email` = ?");

        $query_2->bindValue(1, 1);
        $query_2->bindValue(2, $vendorRegisterEmail);

        $query_2->execute();

        mail($vendorRegisterEmail, $emailToVendorSubject, $emailToVendorBody);

        return true;

      } else {
        return false;
      }

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function checking if the e-mail the partner uses to login is registered in the system.
   *
   * @param vendorLoginEmail
   *
   */
  public function doesVendorLoginEmailExist($vendorLoginEmail) {

    $query = $this->db->prepare("SELECT COUNT(`vendor_id`) FROM `vendors` WHERE `vendor_email`= ?");
    $query->bindValue(1, $vendorLoginEmail);

    try {
      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1) {
        return true;
      } else {
        return false;
      }

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function checking if the e-mail the partner uses to login is confirmed in the system.
   *
   * @param vendorLoginEmail
   *
   */
  public function isVendorLoginEmailConfirmed($vendorLoginEmail) {

    $query = $this->db->prepare("SELECT COUNT(`vendor_id`) FROM `vendors` WHERE `vendor_email`= ? AND `vendor_confirmed` = ?");
    $query->bindValue(1, $vendorLoginEmail);
    $query->bindValue(2, 1);

    try {

      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1) {
        return true;
      } else {
        return false;
      }

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function generating a unique secure login key for the partner.
   *
   * @param vendorLoginEmail
   *
   */
  public function generateVendorLoginKey($vendorLoginEmail) {

    $digits = 6; // The length of the key
    $vendorLoginKey = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT); // Generate the secure login key

    // We e-mail the key to the partner trying to log in
    $emailToVendorSubject = 'eShop - Your login key';
    $emailToVendorBody = "Hi there,\r\n\r\nUse this key to login to your account:\r\n\r\n$vendorLoginKey\r\n\r\nThe eShop Team";

    $query = $this->db->prepare("UPDATE `vendors` SET `vendor_key` = ? WHERE `vendor_email` = ?");

    $query->bindValue(1, $vendorLoginKey);
    $query->bindValue(2, $vendorLoginEmail);

    try {

      $query->execute();

      mail($vendorLoginEmail, $emailToVendorSubject, $emailToVendorBody);

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function checking the login key against the login e-mail
   *
   * @param vendorLoginEmail
   * @param vendorLoginKey
   *
   */
  public function verifyVendorLoginKey($vendorLoginEmail, $vendorLoginKey) {

    $query = $this->db->prepare("SELECT `vendor_key`, `vendor_id` FROM `vendors` WHERE `vendor_email` = ?");

    $query->bindValue(1, $vendorLoginEmail);

    try {

      $query->execute();

      while ( $rows = $query->fetch() ) {

        $keyInDatabase = $rows['vendor_key'];
        $vendorId = $rows['vendor_id'];

        if ($keyInDatabase === $vendorLoginKey) { // We verify the login key
          return $vendorId; // And start a session if the key they entered matches the one stored in the database
        }
        else {
          return false;
        }
      }

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all partner information based on the partner id.
   *
   * @param vendorId
   *
   */
  public function vendorData($vendorId) {

    $query = $this->db->prepare("SELECT * FROM `vendors` WHERE `vendor_id`= ?");
    $query->bindValue(1, $vendorId);

    try {

      $query->execute();

      return $query->fetch();

    } catch(PDOException $e){
      die($e->getMessage());
    }
  }

  /**
   * Function returning all active partners in the system.
   */
  public function getAllVendors() {

    $query = $this->db->prepare("SELECT * FROM `vendors` WHERE `vendor_confirmed` = 1 ORDER BY `vendor_id` ASC");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all partners in the system based on their id.
   */
  public function getAllVendorsById() {

    $query = $this->db->prepare("SELECT `vendor_id` FROM `vendors` ");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function uploading the path to the partner's API
   *
   * @param apiPath
   *   The url to their .json or .xml products file
   *
   * @param vendorId
   *   The id of the logged in partner.
   *
   */
  public function uploadPathToApi($apiPath, $vendorId) {

    $query = $this->db->prepare("UPDATE `vendors` SET `vendor_url` = ? WHERE `vendor_id` = ?");

    $query->bindValue(1, $apiPath);
    $query->bindValue(2, $vendorId);

    try {

      $query->execute();

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning the commission based on a vendor_id.
   *
   * @param vendorId
   *
   */
  public function getCommissionByVendorId($vendorId) {

    $query = $this->db->prepare("SELECT * FROM `vendors` WHERE `vendor_id` = ?");

    $query->bindValue(1, $vendorId);

    try {

      $query->execute();

      while ( $rows = $query->fetch() ) {
        $vendorCommission = $rows['vendor_commission'];
      }

      return $vendorCommission;

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

} 