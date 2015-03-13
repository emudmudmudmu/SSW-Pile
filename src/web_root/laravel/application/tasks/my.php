<?php
use Laravel\Log;
class My_Task {

	public function run($args) {
		Log::debug("TEST");
	}
}
