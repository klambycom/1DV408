<?php

namespace view;

class User {
  public function signInHtml() {
    return "
      <h2>Ej inloggad</h2>
      <form action='?p=login' method='post'>
        <fieldset>
          <legend>Login - Skriv in användarnamn och lösenord</legend>
          <label for='username'>Användarnamn</label>
          <input type='text' size='20' name='username' id='username' />
          <label for='password'>Lösenord</label>
          <input type='password' name='password' id='password' />
          <label for='autologin'>Håll mig inloggad</label>
          <input type='checkbox' name='autologin' id='autologin' />
          <input type='submit' value='Logga in &rarr;' />
        </fieldset>
      </form>
    ";
  }
}
