<?php

namespace view;

class User {
  /**
   * @var string $username Name of the username field.
   */
  private static $username = "view::user::username";

  /**
   * @var string $password Name of the password field.
   */
  private static $password = "view::user::password";

  /**
   * @return Html
   */
  public function login() {
    return "
      <h2>Ej inloggad</h2>
      <form action='?page=login' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
          {$this->getMessage()}
          <label for='" . self::$username . "'>Användarnamn</label>
          <input type='text' name='" . self::$username . "'
                 id='" . self::$username . "' value='{$this->getUsername()}' />
          <label for='" . self::$password . "'>Lösenord</label>
          <input type='password' name='" . self::$password . "'
                 id='" . self::$password . "' />
          <label for='autologin'>Håll mig inloggad</label>
          <input type='checkbox' name='autologin' id='autologin' />
          <input type='submit' value='Logga in &rarr;' />
        </fieldset>
      </form>
    ";
  }

  /**
   * @param \model\User
   * @return Html
   */
  public function member(\model\User $user) {
    return "<h2>{$user->getUsername()} är inloggad</h2>
            {$this->getMessage()}
            <a href='?page=logout'>Logga ut</a>";
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
   * @return string Message if there is any.
   */
  private function getMessage() {
    if (isset($_SESSION["msg"])) {
      return sprintf("<p id='msg'>%s</p>",
                     htmlspecialchars($this->unsetSession("msg")));
    }
    return "";
  }

  /**
   * @param mixed $sessionName Name of the session.
   * @return mixed The value from the session.
   */
  private function unsetSession($sessionName) {
    $ret = $_SESSION[$sessionName];
    unset($_SESSION[$sessionName]);
    return $ret;
  }
}
