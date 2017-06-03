<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'dom.php';
require_once 'user.php';

class AdminPanel {
	function render($r) {
		$r->print("<h2><a target='_blank' href='sql-dump.php'>
			SQL Dump</a></h2>");
		$r->print("<h2><a target='_blank' href='edit-permissions.php'>
			Edit permissions</a></h2>");
		$r->print("<h2><a target='_blank' href='manage-ungraded.php'>
			Manage ungraded submissions</a></h2>");
	}
}

class AdminPanelPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new AdminPanel();
	}
}

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	recover(0);
}

$r = new Renderer(0);
$page = new AdminPanelPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>