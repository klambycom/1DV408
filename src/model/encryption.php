<?php

namespace model;

class Encryption {
  /**
   * @var Key generated from secret passphrase.
   */
  private $key;

  /**
   * @var A random IV?
   */
  private $iv;

  /**
   * @param string $key Secret passphrase
   */
  public function __construct($key) {
    $this->key = hash("sha256", $key, true);

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $this->iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  }

  /**
   * @param string $value
   * @return A encrypted string.
   */
  public function encrypt($value) {
    $text = $this->mcrypt("encrypt", $value);
    return trim(base64_encode($text));
  }

  /**
   * @param string $value
   * @return A decrypted string.
   */
  public function decrypt($value) {
    $text = $this->mcrypt("decrypt", base64_decode($value));
    return trim($text);
  }

  /**
   * @param string $fn encrypt or decrypt
   * @param string $value
   * @return A encrypted or decrypted string.
   */
  private function mcrypt($fn, $value) {
    return call_user_func("mcrypt_$fn",
                          MCRYPT_RIJNDAEL_256,
                          $this->key,
                          $value,
                          MCRYPT_MODE_ECB,
                          $this->iv);
  }
}
