<?php

namespace model;

class User {
  /**
   * @var string $usernameSession Name of the username session.
   */
  private static $usernameSession = "model::user::username";

  /**
   * @var string $passwordSession Name of the password session.
   */
  private static $passwordSession = "model::user::password";

  /**
   * @return boolean Return true if user is authenticated
   */
  public function isLoggedIn() {
    return (isset($_SESSION[self::$usernameSession]) &&
            isset($_SESSION[self::$passwordSession])) &&
           $this->validUserSession();
  }

  /**
   * @param string $username
   * @param string $password
   *
   * @throws Exception if wrong username or password is passed to the function.
   */
  public function login($username, $password) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($username == "Admin" && $password == "Password") {
      $_SESSION[self::$usernameSession] = $username;
      $_SESSION[self::$passwordSession] = $password;
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }
  }

  /**
   * Unset all sessions
   */
  public function logout() {
    unset($_SESSION[self::$usernameSession]);
    unset($_SESSION[self::$passwordSession]);
  }

  /**
   * @return string
   */
  public function getUsername() {
    return isset($_SESSION[self::$usernameSession]) ?
           $_SESSION[self::$usernameSession] : "";
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

  /**
   * @return boolean True if username and password is correct.
   */
  private function validUserSession() {
    return $_SESSION[self::$usernameSession] == "Admin" &&
           $_SESSION[self::$passwordSession] == "Password";
  }
}
