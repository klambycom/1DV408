<?php

namespace view;

class Persistence {
  /**
   * @var string The name of the cookie
   */
  private static $cookie = "persistence::id";

  /**
   * @return Boolean Return if trying to login using cookie
   */
  public function isUsingCookie() {
    return isset($_COOKIE[self::$cookie]);
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
  public function setEncryptedId($id, $time) {
    setcookie(self::$cookie, $id, $time);
  }

  /**
   * Remove user cookie
   */
  public function removeCookie() {
    setcookie(self::$cookie, "", time() - 100);
  }
}
