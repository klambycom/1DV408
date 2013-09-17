<?php
namespace controller;

require_once("view/user.php");
require_once("model/user.php");

class User {
  private $view;
  private $model;

  public function __construct() {
    $this->view = new \view\User();
    $this->model = new \model\User();
  }

  public function startpage() {
    if ($this->model->isLoggedIn()) {
      echo "Logged in";
    } else {
      echo $this->view->login();
    }
  }

  public function loginUser() {
    try {
      $this->model->login($this->view->getUsername(), $this->view->getPassword());
      $this->view->setMessage("Inloggning lyckades");
      echo $this->view->member($this->model);
    } catch (\Exception $e) {
      $this->view->setMessage($e->getMessage());
      echo $this->view->login();
    }
  }
}
