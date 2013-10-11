<?php

namespace model;


/**
 * Model observer
 */
interface StickGameObserver {
	public function playerWins();
	public function playerLoose();
	public function aiRemoved(StickSelection $selection);
	public function aiWinningMessage();
	public function aiLosingMessage();
}
