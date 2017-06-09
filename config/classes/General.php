<?php

class General {

  /**
   * Implements protection for admin.
   */
  public function adminLoggedIn () {
    return(isset($_SESSION['adminId'])) ? true : false; // We return a bool if an admin session is active or not
  }
  // When admin is logged in, we make sure the landing page is home.php
  public function adminLoggedInProtect() {
    if ($this->adminLoggedIn() === true) {
      header('Location: /admin/home.php');
      exit();
    }
  }
  // When admin is logged out, we make sure the landing page is index.php
  public function adminLoggedOutProtect() {
    if ($this->adminLoggedIn() === false) {
      header('Location: /admin/index.php');
      exit();
    }
  }

  /**
   * Implements protection for customers.
   */
  public function userLoggedIn () {
    return(isset($_SESSION['userId'])) ? true : false; // We return a bool if a customer session is active or not
  }
  // When a customer is logged in, we make sure the landing page is home.php
  public function userLoggedInProtect() {
    if ($this->userLoggedIn() === true) {
      header('Location: /views/users/home.php');
      exit();
    }
  }
  // When a customer is logged out, we make sure the landing page is index.php
  public function userLoggedOutProtect() {
    if ($this->userLoggedIn() === false) {
      header('Location: /index.php');
      exit();
    }
  }

  /**
   * Implements protection for partners.
   */
  public function vendorLoggedIn () {
    return(isset($_SESSION['vendorId'])) ? true : false; // We return a bool if a partner session is active or not
  }
  // When a partner is logged in, we make sure the landing page is home.php
  public function vendorLoggedInProtect() {
    if ($this->vendorLoggedIn() === true) {
      header('Location: /views/vendors/home.php');
      exit();
    }
  }
  // When a partner is logged out, we make sure the landing page is index.php
  public function vendorLoggedOutProtect() {
    if ($this->vendorLoggedIn() === false) {
      header('Location: /index.php');
      exit();
    }
  }

} 