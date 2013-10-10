<?php

namespace model;

require_once("model/encryption.php");

class UserEncryption extends Encryption {
  /**
   * @var string $encryption Encrypted json with username, password and time.
   */
  private $encryption;

  /**
   * @var string $username
   */
  private $username;

  /**
   * @var string $password
   */
  private $password;

  /**
   * @var int $time
   */
  private $time;

  /**
   * @var string $secret
   */
  private static $secret;

  /**
   * @param string $username
   * @param string $password
   * @param int    $time
   *
   * @return A \model\UserEncryption where username and password is set.
   */
  public static function encrypt($username, $password, $time) {
    $obj = new UserEncryption(self::$secret);
    $obj->setUsername($username);
    $obj->setPassword($password);
    $obj->setTime($time);
    return $obj;
  }

  /**
   * @param string $encryption Encrypted string.
   *
   * @return A \model\UserEncryption where username and password is set.
   */
  public static function decrypt($encryption) {
    $obj = new UserEncryption(self::$secret);
    $obj->setEncryption($encryption);
    return $obj;
  }

  /**
   * @param stirng $encryption Encrypted string.
   */
  public function setEncryption($encryption) {
    $json = json_decode(parent::makeDecryption($encryption));

    if (!isset($json))
      throw new \Exception("Felaktig information i cookie.");

    if ($json->{"time"} < time())
      throw new \Exception("Krypteringen är för gammal.");

    $this->username = $json->{ "username" };
    $this->password = $json->{ "password" };
  }

  /**
   * @return Encrypted string.
   * @TODO: Check if time, username and password is set.
   */
  public function getEncryption() {
    //assert($this->time);
    //assert($this->username);
    //assert($this->password);

    $json = sprintf('{ "time": %d, "username": "%s", "password": "%s" }',
                    $this->time, $this->username, $this->password);
    return parent::makeEncryption($json);
  }

  /**
   * @param string $username
   */
  public function setUsername($username) {
    $this->username = $username;
  }

  /**
   * @return Username
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @param string $password
   */
  public function setPassword($password) {
    $this->password = $password;
  }

  /**
   * @return Password
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param string $time
   */
  public function setTime($time) {
    $this->time = $time;
  }
}
