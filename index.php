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

$userView = new \view\User();

echo $userView->signInHtml();

$time = new \view\CurrentTime();
echo $time->html();

?>

  </body>
</html>
