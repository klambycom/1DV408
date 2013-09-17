<!doctype html>
<html lang="sv">
  <head>
    <meta charset="utf-8">
    <title>1DV408</title>
  </head>
  <body>
    <h1>1DV408</h1>

<?php
require_once("view/user.php");
require_once("view/currenttime.php");
require_once("model/user.php");

$userView = new \view\User();
$userModel = new \model\User();

if (!isset($_GET["page"])) {
  if ($userModel->isSignedIn()) {
    echo "Signed in";
  } else {
    echo $userView->signIn();
  }
} else {
  switch ($_GET["page"]) {
    case "login":
      try {
        $userModel->signIn($userView->getUsername(), $userView->getPassword());
      } catch (Exception $e) {
        $userView->setMessage($e->getMessage());
        echo $userView->signIn();
      }
      break;

    case "logout":
      echo "logout";
      break;
  }
}

$time = new \view\CurrentTime();
echo $time->html();

?>

  </body>
</html>
