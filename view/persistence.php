<?php

namespace view;

class Persistence {
  /**
   * @var string The name of the cookie
   */
  private static $cookie = "persistence::id";

  /**
   * @var string The name of the username session
   */
  private static $username = "persistence::username";

  /**
   * @var string The name of the password session
   */
  private static $password = "persistence::password";

  /**
   * @var string The name of the user-agent session
   */
  private static $agent = "persistence::user_agent";

  /**
   * @return Boolean Return if trying to login using cookie
   */
  public function isUsingCookie() {
    return isset($_COOKIE[self::$cookie]);
  }

  /**
   * @return Boolean Return if trying to login using session
   */
  public function isUsingSession() {
    return isset($_SESSION[self::$username]) && isset($_SESSION[self::$password]) &&
           $_SESSION[self::$agent] == $_SERVER["HTTP_USER_AGENT"];
  }

  /**
   * @return Boolean Return if trying to login using session or cookie
   */
  public function isInUse() {
    return $this->isUsingCookie() || $this->isUsingSession();
  }

  /**
   * @return string Cookie with user information
   */
  public function getEncryptedId() {
    return $_COOKIE[self::$cookie];
  }

  /**
   * @param string Encrypted string with user information
   */
  public function setEncryptedId($id) {
    setcookie(self::$cookie, $id, time() + 3600 * 24 * 7);
  }

  /**
   * Remove user cookie
   */
  public function removeCookie() {
    setcookie(self::$cookie, "", time() - 100);
  }

  /**
   * @param string $username
   * @param string $password
   */
  public function saveSession($username, $password) {
    $_SESSION[self::$username] = $username;
    $_SESSION[self::$password] = $password;
    $_SESSION[self::$agent] = $_SERVER["HTTP_USER_AGENT"];
  }

  /**
   * Destroy all session with user information
   */
  public function destroySession() {
    unset($_SESSION[self::$username]);
    unset($_SESSION[self::$password]);
    unset($_SESSION[self::$agent]);
  }

  /**
   * @return string Username from session
   */
  public function getUsername() {
    return $_SESSION[self::$username];
  }

  /**
   * @return string Password from session
   */
  public function getPassword() {
    return $_SESSION[self::$password];
  }
}
