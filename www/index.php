<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'loginbox.php';
require_once 'user.php';

class IndexPage extends Page {

	function __construct() {
		parent::__construct();
		if (get_session_id() == 0) {
			$this->body_items[] = new LoginBox();
		} else {
			$user = User::construct_safe(get_session_id());
			// Temporary welcome setup
			$this->body_items[] = new Text("<p>Welcome, ");
			$this->body_items[] = new Adapter($user, "render_link");
		}
	}
}

$r = new Renderer(0);
$page = new IndexPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>