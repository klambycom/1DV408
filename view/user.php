<?php

namespace view;

class User {
  public function login() {
    $html = "
      <h2>Ej inloggad</h2>
      <form action='?page=login' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
    ";

    $html .= $this->getMessage();

    $html .= "
          <label for='username'>Användarnamn</label>
          <input type='text' size='20' name='username' id='username' value='" . $this->getUsername() . "' />
          <label for='password'>Lösenord</label>
          <input type='password' name='password' id='password' />
          <label for='autologin'>Håll mig inloggad</label>
          <input type='checkbox' name='autologin' id='autologin' />
          <input type='submit' value='Logga in &rarr;' />
        </fieldset>
      </form>
    ";

    return $html;
  }

  public function member(\model\User $user) {
    $html  = "<h2>{$user->getUsername()} är inloggad</h2>";
    $html .= $this->getMessage();
    return $html . "<a href='#'>Logga ut</a>";
  }

  public function getUsername() {
    return (isset($_POST["username"])) ? $_POST["username"] : "";
  }

  public function getPassword() {
    return (isset($_POST["password"])) ? $_POST["password"] : "";
  }

  private function getMessage() {
    if (isset($_SESSION["msg"])) {
      $msg = htmlspecialchars($_SESSION["msg"]);
      unset($_SESSION["msg"]);
      return "<p id='msg'>$msg</p>";
    }
    return "";
  }

  public function setMessage($msg) {
    $_SESSION["msg"] = $msg;
  }
}
