<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class LogResetPage extends Page {
	function __construct() {
		parent::__construct();
		file_put_contents(Logger::location(), "");
		$this->body_items[] = new Text("<h2>Log file reset!</h2>");
	}
}

$r = new Renderer(0);
$page = new LogResetPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page log-reset.php");
	recover(0);
}

?>

