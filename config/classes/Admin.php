<?php

class Admin {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function checking if the username is indeed admin.
   *
   * @param adminUsername
   *   The username that is being checked.
   *
   */
  public function doesAdminUsernameExist($adminUsername) {

    $query = $this->db->prepare("SELECT COUNT(*) FROM `admin` WHERE `admin_username`= ?");
    $query->bindValue(1, $adminUsername);

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
   * Function performing the authentication for admin.
   *
   * @param adminUsername
   *   The username used to login.
   *
   * @param adminPassword
   *   The password used to login
   *
   */
  public function loginAdmin($adminUsername, $adminPassword) {

    $query = $this->db->prepare("SELECT COUNT(*) FROM `admin` WHERE `admin_username` = ? AND `admin_password` = ?");
    $query->bindValue(1, $adminUsername);
    $query->bindValue(2, MD5($adminPassword));

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

} 