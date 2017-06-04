<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';
require_once 'user.php';

class LogResetPage extends Page {
	function __construct() {
		parent::__construct();
		file_put_contents(Logger::location(), "");
		$this->body_items[] = new Text("<h2>Log file reset!</h2>");
	}
}

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	Logger::notice('Attempted unauthorized access to log-reset.php');
	recover(0);
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

