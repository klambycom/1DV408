<?php

namespace view;

class User {
  private $message = "";

  public function login() {
    $html = "
      <h2>Ej inloggad</h2>
      <form action='?page=login' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
    ";

    if ($this->message != "") {
      $html .= "<p id='msg'>" . $this->message . "</p>";
    }

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
    $html .= ($this->message == "") ? "" : "<p id='msg'>$this->message</p>";
    return $html . "<a href='#'>Logga ut</a>";
  }

  public function setMessage($msg) {
    $this->message = $msg;
  }

  public function getUsername() {
    return (isset($_POST["username"])) ? $_POST["username"] : "";
  }

  public function getPassword() {
    return (isset($_POST["password"])) ? $_POST["password"] : "";
  }
}
