<?php

namespace controller;

require_once("model/LastStickGame.php");
require_once("view/GameView.php");

class PlayGame {

	/**
	 * @var \model\LastStickGame
	 */
	private $game;

	/**
	 * @var \view\GameView
	 */
	private $view;

	/**
	 * @var string
	 */
	private $message = "";


	public function __construct() {
		$this->game = new \model\LastStickGame();
		$this->view = new \view\GameView($this->game);
	}

	/**
	* @return String HTML
	*/
	public function runGame() {
		//Handle input
		if ($this->game->isGameOver()) {
			$this->doGameOver();
		} else {
			$this->playGame();
		}

		//Generate Output
		return $this->view->show($this->message);
	}

	/**
	* Called when game is still running
	*/
	private function playGame() {
		if ($this->view->playerSelectSticks()) {
			try {
				$sticksDrawnByPlayer = $this->view->getNumberOfSticks();
				$this->game->playerSelectsSticks($sticksDrawnByPlayer, $this->view);
			} catch(\Exception $e) {
				$this->message = "Unauthorized input";
			}
		}
	}

	private function doGameOver() {
		if ($this->view->playerStartsOver()) {
			$this->game->newGame();
		}		
	}
}
