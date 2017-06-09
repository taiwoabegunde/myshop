<?php

include_once 'Product.php';
include_once 'Vendor.php';

class Update {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  /**
   * Function converting our products to JSON and creating/updating the products.json file.
   */
  public function ownProductsToJSON() {

    $ownProductFromDb = new Product($this->db);
    $ownProducts = $ownProductFromDb->getAllOwnProducts(); // First we get all our products

    $allProductsArray = array(); // We create the array to contain our products

    foreach ($ownProducts as $ownProduct) {
      $product = new stdClass(); // We create our product class or object

      $productId = "id";
      $productName = "name";
      $productDescription = "description";
      $productImageUrl = "image";
      $productPrice = "price";

      $product->$productId = $ownProduct['product_id'];
      $product->$productName = $ownProduct['product_name'];
      $product->$productDescription = $ownProduct['product_description'];
      $product->$productImageUrl = $ownProduct['product_image_url'];
      $product->$productPrice = $ownProduct['product_price'];

      array_push($allProductsArray, $product); // We push the products to the array
    }

    $allProductsArrayWithName = array('products' => $allProductsArray);
    $allProducts = json_encode($allProductsArrayWithName); // We encode the data into JSON

    $file = '../api/products.json';
    file_put_contents($file, $allProducts); // And finally we push the data into our products.json file
  }

  /**
   * Function converting our products to XML and creating/updating the products.xml file.
   */
  public function ownProductsToXML() {

    $ownProductFromDb = new Product($this->db);
    $ownProducts = $ownProductFromDb->getAllOwnProducts(); // First we get all our products

    $allProductsXml = new SimpleXMLElement("<products/>"); // We create the <products> XML element to contain our products

    // Then we loop through our products array..
    for($i = 0; $i < count($ownProducts); $i++) {

      $productId = "productId";
      $productName = "name";
      $productDescription = "description";
      $productImageUrl = "image";
      $productPrice = "price";

      // and we create a <product> XML element for every product
      $allProductsXml->addChild("product")->addChild($productId, $ownProducts[$i]['product_id']);
      $allProductsXml->product[$i]->addChild($productName, $ownProducts[$i]['product_name']);
      $allProductsXml->product[$i]->addChild($productDescription, $ownProducts[$i]['product_description']);
      $allProductsXml->product[$i]->addChild($productImageUrl, $ownProducts[$i]['product_image_url']);
      $allProductsXml->product[$i]->addChild($productPrice, $ownProducts[$i]['product_price']);

    }

    $file = '../api/products.xml';
    file_put_contents($file, $allProductsXml->asXML()); // And finally we push the data into our products.xml file

  }

  /**
   * Function taking the partners' products from their API and putting them into our database.
   *
   * @param vendorId
   *   The id of the partner to take products from.
   *
   */
  public function vendorProductsToDb($vendorId) {

    $vendorFromDB = new Vendor($this->db);
    $vendor = $vendorFromDB->vendorData($vendorId); // We get all the information about the partner based on their id
    $vendorApiPath = $vendor['vendor_url'];

    // We check if path to their API is .json or .xml
    if (substr($vendorApiPath,-5) == ".json") {

      $jsonProducts = file_get_contents($vendorApiPath);
      $vendorProducts = json_decode($jsonProducts);

      $vendorProductsToDb = new Product( $this->db );

      for($i = 0; $i < count($vendorProducts->products); $i++) {

        $vendorProduct = $vendorProducts->products[$i];

        $vendorProductId = $vendorProduct->id;
        $vendorProductName = $vendorProduct->name;
        $vendorProductDescription = $vendorProduct->description;
        $vendorProductImageUrl = $vendorProduct->image;
        $vendorProductPrice = $vendorProduct->price;

        // Finally we all the function which adds the products to the database
        $vendorProductsToDb->addProductsFromPartner($vendorId, $vendorProductId, $vendorProductName, $vendorProductDescription, $vendorProductImageUrl, $vendorProductPrice);

      }

    } else if (substr($vendorApiPath,-4) == ".xml") { // Same process if their API is XML

      $xmlProducts = file_get_contents($vendorApiPath);
      $vendorProducts = simplexml_load_string($xmlProducts);

      $vendorProductsToDb = new Product( $this->db );

      foreach ($vendorProducts as $vendorProduct) {

        $vendorProductId = $vendorProduct->productId;
        $vendorProductName = $vendorProduct->name;
        $vendorProductDescription = $vendorProduct->description;
        $vendorProductImageUrl = $vendorProduct->image;
        $vendorProductPrice = $vendorProduct->price;

        $vendorProductsToDb->addProductsFromPartner($vendorId, $vendorProductId, $vendorProductName, $vendorProductDescription, $vendorProductImageUrl, $vendorProductPrice);

      }

    }
  }

  /**
   * Function updating all the vendor products.
   */
  public function updateAllVendorProducts( ){

    $vendorFromDb = new Vendor( $this->db );
    $allVendorsById = $vendorFromDb->getAllVendorsById();

    $updateAllVendors = new Update( $this->db );

    foreach ($allVendorsById as $vendorById) {

      $currentVendorId = $vendorById[0];

      if ($currentVendorId != 28){
        $updateAllVendors->vendorProductsToDb($currentVendorId);
      }

    }

  }

}