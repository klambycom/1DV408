<?php

namespace model;

class User {
  private $signedIn = false;

  public function isSignedIn() {
    return $this->signedIn;
  }

  public function signIn($username, $password) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($username == "Admin" && $password == "Password") {
      $this->signedIn = true;
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }
  }

  private function validate($tests) {
    $errors = array_filter($tests, function ($x) { return !$x; });
    if (sizeof($errors) > 0) {
      throw new \Exception(implode(" och ", array_keys($errors)) . " saknas");
    }
  }
}
