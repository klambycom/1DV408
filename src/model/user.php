<?php
namespace model;

require_once("model/encryption.php");

class User {
  /**
   * @var \model\Encryption
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

  public function __construct() {
    $this->encryption = new Encryption("A secret string i should change");
  }

  /**
   * @param string $username
   * @param string $password
   *
   * @throws Exception if wrong username or password is passed to the function.
   */
  public function login($username, $password) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($this->checkUser($username, $password)) {
      $this->username = $username;
      $this->password = $password;
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }

    return true;
  }

  /**
   * @param string $id
   *
   * @throws Exception if id is wrong.
   */
  public function loginWithId($id) {
    $user = $this->getDecryptedId($id);
    $this->login($user->{"username"}, $user->{"password"});
  }

  /**
   * @return string The username
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @return string The password
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @return string Encrypted string with user information
   */
  public function getEncryptedId($time) {
    $json = sprintf('{ "time": %d, "username": "%s", "password": "%s" }',
                    $time,
                    $this->getUsername(),
                    $this->getPassword());
    return $this->encryption->encrypt($json);
  }

  /**
   * @return json User information in Json format
   */
  private function getDecryptedId($id) {
    $json = json_decode($this->encryption->decrypt($id));

    if (!isset($json))
      throw new \Exception("Felaktig information i cookie.");

    if ($json->{"time"} < time())
      throw new \Exception("Krypteringen är för gammal.");

    return $json;
  }

  /**
   * @param string $username
   * @param string $password
   * @return boolean Return true if username and password is correct
   */
  private function checkUser($username, $password) {
    return $username == "Admin" && $password == "Password";
  }

  /**
   * @param array
   *
   * @throws Exception if one or more test fails.
   */
  private function validate($tests) {
    $errors = array_filter($tests, function ($x) { return !$x; });
    if (sizeof($errors) > 0) {
      throw new \Exception(implode(" och ", array_keys($errors)) . " saknas");
    }
  }
}
