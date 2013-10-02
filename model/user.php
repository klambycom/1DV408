<?php
namespace model;

require_once("model/encryption.php");

class User {
  private $encryption;

  /**
   * @var string $usernameName Name of the username session and cookie.
   */
  private static $usernameName = "model::user::username";

  /**
   * @var string $passwordName Name of the password session and cookie.
   */
  private static $passwordName = "model::user::password";

  private static $useragentName = "model::user::useragent";

  public function __construct() {
    $this->encryption = new Encryption("A secret string i should change");
  }

  /**
   * @return boolean Return true if user is authenticated
   */
  public function isLoggedIn() {
    return (isset($_SESSION[self::$usernameName]) &&
            isset($_SESSION[self::$passwordName]) &&
            isset($_SESSION[self::$useragentName])) &&
           $_SESSION[self::$useragentName] == $_SERVER["HTTP_USER_AGENT"] &&
           $this->checkUser($_SESSION[self::$usernameName],
                            $_SESSION[self::$passwordName]);
  }

  public function loginUsingCookie() {
    $password = $this->decryptPassword($_COOKIE[self::$passwordName]);

    if ($this->checkUser($_COOKIE[self::$usernameName], $password)) {
      $this->saveSession($_COOKIE[self::$usernameName], $password);
    } else {
      throw new \Exception("Felaktig information i cookie");
    }
  }

  public function isSavedInCookie() {
    return (isset($_COOKIE[self::$usernameName]) &&
            isset($_COOKIE[self::$passwordName]));
  }

  /**
   * @param string $username
   * @param string $password
   *
   * @throws Exception if wrong username or password is passed to the function.
   */
  public function login($username, $password, $rememberMe = false) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($this->checkUser($username, $password)) {
      $this->saveSession($username, $password);
      if ($rememberMe) $this->saveCookie($username, $password);
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }
  }

  /**
   * Unset all sessions
   */
  public function logout() {
    unset($_SESSION[self::$usernameName]);
    unset($_SESSION[self::$passwordName]);
    setcookie(self::$usernameName, "", time() - 3600);
    setcookie(self::$passwordName, "", time() - 3600);
  }

  /**
   * @return string
   */
  public function getUsername() {
    return isset($_SESSION[self::$usernameName]) ?
           $_SESSION[self::$usernameName] : "";
  }

  /**
   * @param string $username
   * @param string $password
   */
  private function saveSession($username, $password) {
    $_SESSION[self::$usernameName] = $username;
    $_SESSION[self::$passwordName] = $password;
    $_SESSION[self::$useragentName] = $_SERVER["HTTP_USER_AGENT"];
  }

  /**
   * @param string $username
   * @param string $password
   */
  private function saveCookie($username, $password) {
    $expiration = time() + 3600 * 24 * 7;
    $encrypted = $this->encryptPassword($password, $expiration);

    setcookie(self::$usernameName, $username, $expiration);
    setcookie(self::$passwordName, $encrypted, $expiration);
  }

  private function encryptPassword($password, $date) {
    $json = sprintf('{ "time": %d, "password": "%s"}', $date, $password);
    return $this->encryption->encrypt($json);
  }

  private function decryptPassword($password) {
    $json = json_decode($this->encryption->decrypt($password));

    if ($json->{"time"} < time())
      throw new \Exception("Felaktig information i cookie");

    return $json->{"password"};
  }

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
