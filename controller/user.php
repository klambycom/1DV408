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
    } else if ($this->model->isSavedInCookie()) {
      $this->loginUsingCookie();
    } else {
      echo $this->view->login();
    }
  }

  private function loginUsingCookie() {
    try {
      $this->model->loginUsingCookie();
      $this->redirectWithMessage("/", "Inloggning lyckades via cookies.");
    } catch (\Exception $e) {
      $this->model->logout();
      $this->redirectWithMessage("/", $e->getMessage());
    }
  }

  /**
   * Login user or show error message, and redirect to frontpage.
   */
  public function loginUser() {
    try {
      $this->model->login($this->view->getUsername(),
                          $this->view->getPassword(),
                          $this->view->isRememberMeChecked());

      if ($this->view->isRememberMeChecked()) {
        $msg = "och vi kommer ihåg dig nästa gång.";
      }

      $this->redirectWithMessage("/", "Inloggning lyckades $msg");
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
