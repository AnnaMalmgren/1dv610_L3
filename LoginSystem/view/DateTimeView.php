<?php

namespace View;

class DateTimeView {
	private $timeStringConf = 'l\, \t\h\e jS \of F Y\, \T\h\e \t\i\m\e \i\s H:i:s';

	public function show() : string {

		$timeString = date($this->timeStringConf);

		return '<p>' . $timeString . '</p>';
	}
}