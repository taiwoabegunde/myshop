<?php

class Order {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function creating a new order in the system.
   */
  public function addNewOrder($newOrderVendorId, $newOrderUserId, $newOrderProductId, $newOrderProductQuantity, $newOrderTotal, $newOrderDeliveryAddress, $newOrderEmail, $newOrderPhoneNumber) {

    $newOrderTime = time(); // We generate the date and time the order has been created

    $query = $this->db->prepare("INSERT INTO `orders` (`order_partner_id`, `order_customer_id`, `order_product_id`, `order_product_quantity`, `order_total`, `order_delivery_address`, `order_email`, `order_phone_number`, `order_timestamp`, `order_processed`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $query->bindValue(1, $newOrderVendorId);
    $query->bindValue(2, $newOrderUserId);
    $query->bindValue(3, $newOrderProductId);
    $query->bindValue(4, $newOrderProductQuantity);
    $query->bindValue(5, $newOrderTotal);
    $query->bindValue(6, $newOrderDeliveryAddress);
    $query->bindValue(7, $newOrderEmail);
    $query->bindValue(8, $newOrderPhoneNumber);
    $query->bindValue(9, $newOrderTime);
    $query->bindValue(10, 0);

    try {

      $query->execute();

    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all own orders (orders where the order_partner_id = 28).
   */
  public function getAllOwnOrders() {

    $query = $this->db->prepare("SELECT * FROM `orders` WHERE `order_partner_id` = 28");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning the name of a product in an order based on the order_product_id.
   */
  public function getProductNameInOrdersByProductId() {

    $query = $this->db->prepare("SELECT o.order_id, p.product_name FROM orders o JOIN products p ON o.order_product_id = p.product_id");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning all partner orders (orders where the order_partner_id != 28).
   */
  public function getAllPartnerOrders() {

    $query = $this->db->prepare("SELECT * FROM `orders` WHERE `order_partner_id` <> 28");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  /**
   * Function returning the name and price of a partner product in an order based on the order_product_id.
   */
  public function getProductNameAndPriceInPartnerOrdersByProductId() {

    $query = $this->db->prepare("SELECT o.order_id, p.product_name, p.product_price FROM orders o JOIN products p ON o.order_product_id = p.ext_product_id AND o.order_partner_id = p.vendor_id ORDER BY o.order_id");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

} 