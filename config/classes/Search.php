<?php

class Search {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function searching for products in the system based on a keyword(s).
   *
   * @param q
   *   The keyword(s) the user typed in the search field.
   *
   */
  public function searchProducts($q) {

    // The query will look for matches in the product_name, product_description and product_price columns
    // And it will return all available data for that product
    $query = $this->db->prepare("SELECT * FROM `products` WHERE (`product_name` LIKE '%" . $q . "%') OR (`product_description` LIKE '%" . $q . "%') OR (`product_price` LIKE '%" . $q . "%')");

    try {

      $query->execute();

      $result = $query->fetchAll();
      return $result;

    } catch (PDOException $e) {
      die($e->getMessage());
    }

  }

}
