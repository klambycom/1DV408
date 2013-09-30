<?php

namespace view;

class CurrentTime {
  /**
   * @var array Weekdays in short form, starting with 0 => Sön.
   */
  private $days = array("Sön", "Mån", "Tis", "Ons", "Tors", "Fre", "Lör");

  /**
   * @var array Months, starting with 1 => Januari.
   */
  private $months = array("", "Januari", "Februari", "Mars", "April", "Maj",
                          "Juni", "Juli", "Augusti", "September", "Oktober",
                          "November", "December");

  /**
   * @return string Current time
   */
  public function html() {
    return "<p>
              {$this->weekday()}, {$this->date()}.
              Klockan är [" . date("G:i:s") . "].
            </p>";
  }

  /**
   * @return string Weekday.
   */
  private function weekday() {
    return $this->days[date("w")] . "dag";
  }

  /**
   * @return string Date with day, month and year.
   */
  private function date() {
    return sprintf("den %s %s år %s",
                   date("j"), $this->months[date("n")], date("Y"));
  }
}
