<?php

namespace view;

class User {
  private static $username = "usernameField";
  private static $password = "passwordField";

  /**
   * @return Html
   */
  public function login() {
    $html = "
      <h2>Ej inloggad</h2>
      <form action='?page=login' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
    ";

    $html .= $this->getMessage();

    $html .= "
          <label for='" . self::$username . "'>Användarnamn</label>
          <input type='text' size='20' name='" . self::$username . "' id='" . self::$username . "' value='" . $this->getUsername() . "' />
          <label for='" . self::$password . "'>Lösenord</label>
          <input type='password' name='" . self::$password . "' id='" . self::$password . "' />
          <label for='autologin'>Håll mig inloggad</label>
          <input type='checkbox' name='autologin' id='autologin' />
          <input type='submit' value='Logga in &rarr;' />
        </fieldset>
      </form>
    ";

    return $html;
  }

  /**
   * @param \model\User
   * @return Html
   */
  public function member(\model\User $user) {
    $html  = "<h2>{$user->getUsername()} är inloggad</h2>";
    $html .= $this->getMessage();
    return $html . "<a href='?page=logout'>Logga ut</a>";
  }

  /**
   * @return string
   */
  public function getUsername() {
    if (isset($_SESSION[self::$username]) && !isset($_POST[self::$username])) {
      return $this->unsetSession(self::$username);
    } else if (isset($_POST[self::$username])) {
      $_SESSION[self::$username] = $_POST[self::$username];
      return $_POST[self::$username];
    }
    return "";
  }

  /**
   * @return string
   */
  public function getPassword() {
    return (isset($_POST[self::$password])) ? $_POST[self::$password] : "";
  }

  /**
   * @param string $msg The message to be displayed
   */
  public function setMessage($msg) {
    $_SESSION["msg"] = $msg;
  }

  /**
   * @return string
   */
  private function getMessage() {
    if (isset($_SESSION["msg"])) {
      return "<p id='msg'>" . htmlspecialchars($this->unsetSession("msg")) . "</p>";
    }
    return "";
  }

  /**
   * @param mixed
   * @return mixed
   */
  private function unsetSession($var) {
    $ret = $_SESSION[$var];
    unset($_SESSION[$var]);
    return $ret;
  }
}
