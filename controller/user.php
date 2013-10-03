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
    $this->persistence->destroy();
    $this->redirect("/", "Du har nu loggat ut");
  }

  /**
   * Show login form if not logged in, else member area.
   */
  public function doStartpage() {
    if ($this->persistence->isInUse()) {
      $this->loginUsingPersistence();
    } else {
      echo $this->view->login();
    }
  }

  /**
   * Login using $_SESSION or $_COOKIE
   */
  private function loginUsingPersistence() {
      if ($this->persistence->isUsingSession()) {
        $this->login("session");
      } else {
        $this->login("cookie");
        $this->view->setMessageDirect("Inloggning lyckades via cookie.");
      }

      echo $this->view->member($this->model);
  }


  /**
   * Login user with $_POST or show error message, and redirect to frontpage.
   */
  public function doLogin() {
    $message = $this->login("post");
    $this->redirect("/", $message);
  }

  /**
   * @param string $method Post, session or cookie
   */
  private function login($method) {
    try {
      $temp = 'loginUsing' . ucfirst($method);
      $message = $this->$temp();

      $this->persistence->saveSession($this->model->getUsername(),
                                      $this->model->getPassword());

      return $message;
    } catch (\Exception $e) {
      $this->persistence->destroy();
      $this->redirect("/", $e->getMessage());
    }
  }

  /**
   * Login using $_POST
   * @return string Message to show user
   */
  private function loginUsingPost() {
    $this->model->login($this->view->getUsername(),
                        $this->view->getPassword());

    $msg = "Inloggning lyckades.";

    if ($this->view->isRememberMeChecked()) {
      $time = time() + 3600 * 24 * 7;
      $this->persistence->setEncryptedId($this->model->getEncryptedId($time),
                                         $time);
      $msg = "Inloggning lyckades och vi kommer ihåg dig nästa gång.";
    }

    return $msg;
  }

  /**
   * Login using $_COOKIE
   * @throws Exception if information is incorrect
   */
  private function loginUsingCookie() {
    try {
      $this->model->loginWithId($this->persistence->getEncryptedId());
    } catch (\Exception $e) {
      $this->persistence->removeCookie();
      throw new \Exception("Felaktig information i cookie");
    }
  }

  /**
   * Login using $_SESSION
   */
  private function loginUsingSession() {
    $this->model->login($this->persistence->getUsername(),
                        $this->persistence->getPassword());
  }

  /**
   * @param string $url
   * @param string $message Optional
   */
  private function redirect($url, $message = "") {
    if ($message != "") {
      $this->view->setMessage($message);
    }
    header("Location: $url");
    exit;
  }
}
