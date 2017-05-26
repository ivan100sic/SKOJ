<?php

require_once 'dom.php';
require_once 'paginate.php';
require_once 'renderer.php';
require_once 'global.php';

class UsersPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('user_simple'));
	}
}

$pg = new UsersPage();
$r = new Renderer(0);
try {
	$pg->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>
