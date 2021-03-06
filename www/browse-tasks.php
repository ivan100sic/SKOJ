<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'paginate.php';
require_once 'logger.php';

class BrowseTasksPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Browse tasks</h2>");
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('task_detailed'));
	}
}

$r = new Renderer(0);
$page = new BrowseTasksPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error('Exception occurred on page browse-tasks.php');
	recover(0);
}