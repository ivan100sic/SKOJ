<?php

require_once 'global.php';

class Logger {

	static function location() {
		return '../logs/default.txt';
	}

	static function log_raw($s) {
		error_log($s, 3, Logger::location());
	}

	static function log($s, $level = 'Error') {
		$dt = date("Y-m-d H:i:s");
		$sid = get_session_id();
		Logger::log_raw("[$level] [$dt] [session: $sid]: $s\n");
	}

	static function error($s) {
		Logger::log($s, 'Error');
	}

	static function critical($s) {
		Logger::log($s, 'CRITICAL');
	}

	static function notice($s) {
		Logger::log($s, 'Notice');
	}

	static function warning($s) {
		Logger::log($s, 'Warning');
	}
}



?>