<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'paginate.php';

class BrowseTasksPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('task_detailed'));
	}
}

$r = new Renderer(0);
$page = new BrowseTasksPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}