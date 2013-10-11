<?php

namespace model;

require_once("model/StickSelection.php");
require_once("model/AIPlayer.php");
require_once("model/PersistantSticks.php");

class LastStickGame {
	const StartingNumberOfSticks = 22;

	/**
	 * @var \model\AIPlayer
	 */
	private $ai;

	/**
	 * @var \model\Sticks
	 */
	private $sticks;

	public function __construct() {
		$this->ai = new AIPlayer();
		$this->sticks = new PersistantSticks(self::StartingNumberOfSticks);
	}

	public function playerSelectsSticks(StickSelection $playerSelection, StickGameObserver $observer) {
		$this->sticks->removeSticks($playerSelection);

		if ($this->isGameOver()) {
			$observer->playerWins();
		} else {
			$this->AIPlayerTurn($observer);
		} 
	}	

	private function AIPlayerTurn(StickGameObserver $observer) {
		$sticksLeft = $this->getNumberOfSticks();
		$selection = $this->ai->getSelection($sticksLeft);
		
		$this->sticks->removeSticks($selection);
		$observer->aiRemoved($selection);

		if ($this->isGameOver()) {
			$observer->playerLoose();
		}

		if ($this->ai->hasAdvantage()) {
			$observer->aiWinningMessage();
		} else {
			$observer->aiLosingMessage();
		}
	}

	/** 
	* @return boolean
	*/
	public function isGameOver() {
		return $this->sticks->getNumberOfSticks() < 2;
	}

	/** 
	* @return int
	*/
	public function getNumberOfSticks() {
		return $this->sticks->getNumberOfSticks();
	}

	public function newGame() {
		$this->sticks->newGame(self::StartingNumberOfSticks);
	}
}
