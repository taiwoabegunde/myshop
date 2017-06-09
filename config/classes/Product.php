<?php

class Product {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function returning the number of products in the system.
   */
  public function getNumberOfRows() {

    $query = $this->db->prepare("SELECT COUNT(*) FROM `products`");

    try {

      $query->execute();
      $rows = $query->fetchColumn();

      return $rows;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function creating a new product in the system.
   */
  public function addNewOwnProduct($newProductName, $newProductDescription, $newProductImage, $newProductQuantity, $newProductPrice) {

    $query = $this->db->prepare("INSERT INTO `products` (`product_name`, `product_description`, `product_image_url`, `product_quantity`, `product_price`, `vendor_id`) VALUES ( ?, ?, ?, ?, ?, ? ) ");

    $query->bindValue(1, $newProductName);
    $query->bindValue(2, $newProductDescription);
    $query->bindValue(3, $newProductImage);
    $query->bindValue(4, $newProductQuantity);
    $query->bindValue(5, $newProductPrice);
    $query->bindValue(6, 28);

    try {

      $query->execute();

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all active products in the system.
   */
  public function getAllProducts() {

    $query = $this->db->prepare("SELECT * FROM `products` WHERE `product_removed` = 0");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all own active products in the system (vendor_id = 28).
   */
  public function getAllOwnProducts() {

    $query = $this->db->prepare("SELECT * FROM `products` WHERE `vendor_id` = 28 AND `product_removed` = 0");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function removing a product from the system (product_removed = 1).
   *
   * @param productId
   *   The id of the product to be removed.
   *
   */
  public function removeProduct($productId) {

    $query = $this->db->prepare("SELECT COUNT(*) FROM `products` WHERE `product_id` = ? AND `product_removed` = ?");

    $query->bindValue(1, $productId);
    $query->bindValue(2, 0);

    try {

      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1) {

        $query_2 = $this->db->prepare("UPDATE `products` SET `product_removed` = ? WHERE `product_id` = ?");

        $query_2->bindValue(1, 1);
        $query_2->bindValue(2, $productId);

        $query_2->execute();
        return true;

      } else {
        return false;
      }

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function adding a product from a partner to our system.
   */
  public function addProductsFromPartner($vendorId, $productId, $productName, $productDescription, $productImageUrl, $productPrice){

    $query = $this->db->prepare("INSERT INTO `products` (`vendor_id`, `ext_product_id`, `product_name`, `product_description`, `product_image_url`, `product_price`) VALUES ( ?, ?, ?, ?, ?, ? ) ");

    $query->bindValue(1, $vendorId);
    $query->bindValue(2, $productId);
    $query->bindValue(3, $productName);
    $query->bindValue(4, $productDescription);
    $query->bindValue(5, $productImageUrl);
    $query->bindValue(6, $productPrice);

    try {

      $query->execute();

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all products from partners (vendor_id != 28).
   */
  public function getAllVendorProducts() {

    $query = $this->db->prepare("SELECT * FROM `products` WHERE `vendor_id` <> 28 AND `product_removed` = 0");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }

  }

  /**
   * Function returning products based on the partner id (they are displayed when a partner logs in)
   *
   * @param vendorId
   *   The id of the partner we want to display products for.
   *
   */
  public function getVendorProductsByVendorId($vendorId) {

    $query = $this->db->prepare("SELECT * FROM `products` WHERE `vendor_id` = ? AND `product_removed` = 0");

    $query->bindValue(1, $vendorId);

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function editing an existing product in the system.
   */
  public function editOwnProduct($editProductName, $editProductDescription, $editProductImage, $editProductQuantity, $editProductPrice, $productId) {

    $query = $this->db->prepare("UPDATE `products` SET `product_name` = ?, `product_description` = ?, `product_image_url` = ?, `product_quantity` = ?, `product_price` = ? WHERE `product_id` = ?");

    $query->bindValue(1, $editProductName);
    $query->bindValue(2, $editProductDescription);
    $query->bindValue(3, $editProductImage);
    $query->bindValue(4, $editProductQuantity);
    $query->bindValue(5, $editProductPrice);
    $query->bindValue(6, $productId);


    try {

      $query->execute();

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

} 