<?php
namespace model;

require_once("model/userencryption.php");

class User {
  /**
   * @var string $username
   */
  private $username;

  /**
   * @var string $password
   */
  private $password;

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
   * @param string $id
   *
   * @throws Exception if id is wrong.
   */
  public function loginUsingEncryption($id) {
    $encryption = UserEncryption::decrypt($id);
    $this->login($encryption->getUsername(), $encryption->getPassword());
  }

  /**
   * @return string Encrypted string with user information
   */
  public function getEncryption($time) {
    $encryption = UserEncryption::encrypt($this->getUsername(),
                                          $this->getPassword(),
                                          $time);
    return $encryption->getEncryption();
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
