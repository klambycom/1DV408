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
      echo $this->view->member($this->model);
    } else {
      echo $this->view->login();
    }
  }

  public function loginUser() {
    try {
      $this->model->login($this->view->getUsername(), $this->view->getPassword());
      $this->redirectWithMessage("Inloggning lyckades", "/");
    } catch (\Exception $e) {
      $this->redirectWithMessage($e->getMessage(), "/");
    }
  }

  private function redirectWithMessage($message, $url) {
    $this->view->setMessage($message);
    header("Location: $url");
    exit;
  }
}
