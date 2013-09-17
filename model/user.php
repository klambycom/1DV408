<?php

namespace model;

class User {
  private $loggedIn = false;

  public function isLoggedIn() {
    return $this->loggedIn;
  }

  public function login($username, $password) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($username == "Admin" && $password == "Password") {
      $this->loggedIn = true;
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }
  }

  public function getUsername() {
    return "Admin";
  }

  private function validate($tests) {
    $errors = array_filter($tests, function ($x) { return !$x; });
    if (sizeof($errors) > 0) {
      throw new \Exception(implode(" och ", array_keys($errors)) . " saknas");
    }
  }
}
