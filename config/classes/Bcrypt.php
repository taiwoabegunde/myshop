<?php

class Bcrypt {

  private $rounds;

  public function __construct($rounds = 12) {
    if(CRYPT_BLOWFISH != 1) {
      throw new Exception("Bcrypt is not supported on this server, please see the following to learn more: http://php.net/crypt");
    }
    $this->rounds = $rounds;
  }

  /**
   * Function generating the salt for passwords.
   */
  private function genSalt() {
    $string = str_shuffle(mt_rand());
    $salt 	= uniqid($string ,true);

    return $salt;
  }

  /**
   * Function encrypting the passwords.
   *
   * @param password
   *   The password that needs to be encrypted.
   *
   * @return string
   *    The hash.
   *
   */
  public function genHash($password) {
    $hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());

    return $hash;
  }

  /**
   * Function verifying the password against the generated hash.
   *
   * @param password
   *   The password to be verified.
   *
   * @param existingHash
   *   The generated hash for the password.
   *
   * @return bool
   *
   */
  public function verify($password, $existingHash) {
    $hash = crypt($password, $existingHash);

    if($hash === $existingHash) {
      return true;
    } else {
      return false;
    }
  }

} 