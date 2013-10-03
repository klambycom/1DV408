<?php
namespace controller;

require_once("view/user.php");
require_once("view/persistence.php");
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

  /**
   * @var \model\Persistence $persistence Handles cookies and sessions.
   */
  private $persistence;

  /**
   * Make instances of View, Model and Persistence.
   */
  public function __construct() {
    $this->view = new \view\User();
    $this->model = new \model\User();
    $this->persistence = new \view\Persistence();
  }

  /**
   * Logout user and redirect to frontpage.
   */
  public function doLogout() {
    $this->persistence->removeCookie();
    $this->persistence->destroySession();
    $this->view->saveMessage("Du har nu loggat ut");
    header("Location: /");
    exit;
  }

  /**
   * Show login form if not logged in, else member area.
   */
  public function doStartpage() {
    if ($this->persistence->isInUse() || $this->view->isLoggingIn()) {
      if ($this->login()) {
        echo $this->view->member($this->model);
      } else {
        echo $this->view->login();
      }
    } else {
      echo $this->view->login();
    }
  }

  /**
   * Login user or show error message, and redirect to frontpage.
   */
  private function login() {
    try {
      if ($this->persistence->isUsingSession()) {
        $this->loginWithSession();
      } else if ($this->persistence->isUsingCookie()) {
        $this->loginWithCookie();
      } else if ($this->view->isLoggingIn()) {
        $this->loginWithPost();
      } else {
        return false;
      }

      $this->persistence->saveSession($this->model->getUsername(),
                                      $this->model->getPassword());

      return true;
    } catch (\Exception $e) {
      $this->view->setMessage($e->getMessage());
    }
  }

  /**
   * Login with $_POST
   */
  private function loginWithPost() {
    $this->model->login($this->view->getUsername(),
                        $this->view->getPassword());

    if ($this->view->isRememberMeChecked()) {
      $this->persistence->setEncryptedId($this->model->getEncryptedId());
      $this->view->setMessage("Inloggning lyckades och vi kommer ihåg dig nästa gång.");
    } else {
      $this->view->setMessage("Inloggning lyckades.");
    }
  }

  /**
   * Login with $_COOKIE
   * @throws Exception if information is incorrect
   */
  private function loginWithCookie() {
    try {
      $this->model->loginWithId($this->persistence->getEncryptedId());
      $this->view->setMessage("Inloggning lyckades via cookie.");
    } catch (\Exception $e) {
      $this->persistence->removeCookie();
      throw new \Exception("Felaktig information i cookie");
    }
  }

  /**
   * Login with $_SESSION
   */
  private function loginWithSession() {
    $this->model->login($this->persistence->getUsername(),
                        $this->persistence->getPassword());
  }
}
