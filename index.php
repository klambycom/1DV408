<?php
session_start();

require_once("controller/user.php");
require_once("view/currenttime.php");

$routes = array("start"  => "startpage",
                "login"  => "loginUser",
                "logout" => "logoutUser");

$page = isset($_GET["page"]) ? $_GET["page"] : "start";

$controller = new \controller\User();
?>
<!doctype html>
<html lang="sv">
  <head>
    <meta charset="utf-8">
    <title>1DV408</title>
  </head>
  <body>
    <h1>1DV408</h1>
    <?php
    echo $controller->$routes[$page]();

    $time = new \view\CurrentTime();
    echo $time->html();
    ?>
  </body>
</html>
