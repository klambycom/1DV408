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
   * @var string $rememberMe Name of the remember me field.
   */
  private static $rememberMe = "view::user::rememberMe";

  /**
   * @var string $message
   */
  private $message;

  public function __construct() {
    if (isset($_SESSION["msg"])) {
      $this->message = $this->unsetSession("msg");
    }
  }

  /**
   * @return Html
   */
  public function login() {
    return "
      <h2>Ej inloggad</h2>
      <form action='' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
          {$this->getMessage()}
          <label for='" . self::$username . "'>Användarnamn</label>
          <input type='text' name='" . self::$username . "'
                 id='" . self::$username . "' value='{$this->getUsername()}' />
          <label for='" . self::$password . "'>Lösenord</label>
          <input type='password' name='" . self::$password . "'
                 id='" . self::$password . "' />
          <label for='" . self::$rememberMe . "'>Håll mig inloggad</label>
          <input type='checkbox' name='" . self::$rememberMe . "'
                 id='" . self::$rememberMe . "' />
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
   * @return boolean Return true if remember me is checked
   */
  public function isRememberMeChecked() {
    return isset($_POST[self::$rememberMe]);
  }

  /**
   * @return boolean Return true if user is trying to login
   */
  public function isLoggingIn() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  /**
   * @param string $msg The message to be displayed
   */
  public function setMessage($msg) {
    $this->message = $msg;
  }

  /**
   * @param string $msg The message to be saved and displayed
   */
  public function saveMessage($msg) {
    $_SESSION["msg"] = $msg;
  }

  /**
   * @return string Message if there is any.
   */
  private function getMessage() {
    if (!empty($this->message)) {
      return sprintf("<p id='msg'>%s</p>", htmlspecialchars($this->message));
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
