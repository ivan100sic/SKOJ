<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'paginate.php';

class RecentSubmissionsPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Recent submissions</h2>");
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('all_subs'));
	}
}

$r = new Renderer(0);
$page = new RecentSubmissionsPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>