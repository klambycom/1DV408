1DV408NOTMVCHT2013
==================

This code is almost an MVC but has 9 errors, can you find them all?

https://coursepress.lnu.se/kurs/webbutveckling-med-php/laborationsmiljo/laboration-3-arkitektur/



9 fel
=====
* \model\LastStickGame.php innehaller reference till \view\GameView::StartingNumberOfSticks. x 2
* \model\AIPlayer.php innehaller HTML. x 2
* \controller\PlayGame.php innehaller HTML.
* \controller\PlayGame.php anvander $_GET! x 3
* \view\GameView.php haller reda pa hur manga streck spelet ska borja med, jag tycker det ar en models uppgift.
