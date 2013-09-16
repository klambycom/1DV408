<?php

namespace view;

class CurrentTime {
  private $days = array("Sön", "Mån", "Tis", "Ons", "Tors", "Fre", "Lör");

  private $months = array("", "Januari", "Februari", "Mars", "April", "Maj", "Juni",
                          "Juli", "Augusti", "September", "Oktober", "November",
                          "December");

  public function html() {
    return $this->days[date("w")] . "dag, den " . date("j") . " " .
           $this->months[date("n")] . " år " . date("Y") . ". Klockan är [" .
           date("G:i:s") . "].";
  }
}
