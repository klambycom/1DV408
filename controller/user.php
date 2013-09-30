<?php
namespace controller;

require_once("view/user.php");
require_once("model/user.php");

class User {
  /**
   * @var \view\User $view The user view.
   */
  private $view;

  /**
   * @var \model\User $model The user model.
   */
  private $model;

  public function __construct() {
    $this->view = new \view\User();
    $this->model = new \model\User();
  }

  /**
   * Show login form if not logged in, else member area.
   */
  public function startpage() {
    if ($this->model->isLoggedIn()) {
      echo $this->view->member($this->model);
    } else {
      echo $this->view->login();
    }
  }

  /**
   * Login user or show error message, and redirect to frontpage.
   */
  public function loginUser() {
    try {
      $this->model->login($this->view->getUsername(),
                          $this->view->getPassword());
      $this->redirectWithMessage("/", "Inloggning lyckades");
    } catch (\Exception $e) {
      $this->redirectWithMessage("/", $e->getMessage());
    }
  }

  /**
   * Logout user and redirect to frontpage.
   */
  public function logoutUser() {
    $this->model->logout();
    $this->redirectWithMessage("/", "Du har nu loggat ut");
  }

  /**
   * @param string $url
   * @param string $message
   */
  private function redirectWithMessage($url, $message) {
    $this->view->setMessage($message);
    header("Location: $url");
    exit;
  }
}
