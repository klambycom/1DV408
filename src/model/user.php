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
   * @var string The name of the username session
   */
  private static $susername = "User::username";

  /**
   * @var string The name of the password session
   */
  private static $spassword = "User::password";

  /**
   * @var string The name of the user-agent session
   */
  private static $sagent = "User::user_agent";

  /**
   * @param string $username
   * @param string $password
   *
   * @throws Exception if wrong username or password is passed to the function.
   */
  public function login($username = "", $password = "") {
    if ($username == "" && $password == "") {
      $username = isset($_SESSION[self::$susername]) ?
                    $_SESSION[self::$susername] : "";
      $password = isset($_SESSION[self::$spassword]) ?
                    $_SESSION[self::$spassword] : "";
    }

    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($this->checkUser($username, $password)) {
      $this->username = $username;
      $this->password = $password;
      $this->saveSession($username, $password);
    } else {
      $this->destroySession();
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }

    return true;
  }

  /**
   * @return boolean
   */
  public static function session() {
    return isset($_SESSION[self::$susername]) &&
           isset($_SESSION[self::$spassword]) &&
           $_SESSION[self::$sagent] == $_SERVER["HTTP_USER_AGENT"];
  }

  /**
   * @param string $username
   * @param string $password
   */
  public function saveSession($username, $password) {
    $_SESSION[self::$susername] = $username;
    $_SESSION[self::$spassword] = $password;
    $_SESSION[self::$sagent] = $_SERVER["HTTP_USER_AGENT"];
  }

  /**
   * Destroy all session with user information
   */
  public function destroySession() {
    unset($_SESSION[self::$susername]);
    unset($_SESSION[self::$spassword]);
    unset($_SESSION[self::$sagent]);
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
