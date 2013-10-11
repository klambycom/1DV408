<?php

namespace model;

class AIPlayer {
	private $difference;
	
	/**
	* Slightly evil AI player
	* @param int $amountOfSticksLeft
	* @return \model\StickSelection
	*/
	public function getSelection($amountOfSticksLeft) {
		$desiredAmountAfterDraw = array(21, 17, 13, 9, 5, 1);

		foreach ($desiredAmountAfterDraw as $desiredStics) {
			if ($amountOfSticksLeft > $desiredStics ) {
				$this->difference = $amountOfSticksLeft - $desiredStics;
				break;
			}
		}

		if ($this->hasAdvantage())
			$drawInteger = $this->difference;
		else
			$drawInteger = rand() % 3 + 1;

		//change from integer into valid StickSelection
		switch ($drawInteger) {
			case 1 : return StickSelection::One(); break;
			case 2 : return StickSelection::Two(); break;
			case 3 : return StickSelection::Three(); break;
		}

		//should never go here
		assert(false); 
	}

	public function hasAdvantage() {
		return !($this->difference > 3 || $this->difference < 1);
	}
}
