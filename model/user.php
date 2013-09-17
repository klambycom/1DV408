<?php

namespace model;

class User {
  public function isLoggedIn() {
    return ((isset($_SESSION["username"]) && isset($_SESSION["password"])) &&
            ($_SESSION["username"] == "Admin" && $_SESSION["password"] == "Password"));
  }

  public function login($username, $password) {
    $this->validate(array("Användarnamn" => !empty($username),
                          "Lösenord"     => !empty($password)));

    if ($username == "Admin" && $password == "Password") {
      $_SESSION["username"] = $username;
      $_SESSION["password"] = $password;
    } else {
      throw new \Exception("Felaktigt användarnamn och/eller lösenord");
    }
  }

  public function logout() {
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
  }

  public function getUsername() {
    return isset($_SESSION["username"]) ? $_SESSION["username"] : "";
  }

  private function validate($tests) {
    $errors = array_filter($tests, function ($x) { return !$x; });
    if (sizeof($errors) > 0) {
      throw new \Exception(implode(" och ", array_keys($errors)) . " saknas");
    }
  }
}
