<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'loginbox.php';
require_once 'user.php';
require_once 'logger.php';

class IndexPage extends Page {

	function __construct() {
		parent::__construct();
		if (get_session_id() == 0) {
			$this->body_items[] = new LoginBox();
		} else {
			$user = User::construct_safe(get_session_id());
			// Welcome
			$this->body_items[] = new Text("<h2>Welcome, ");
			$this->body_items[] = new Adapter($user, "render_link");
			$this->body_items[] = new Text("</h2>");
		}
	}
}

$r = new Renderer(0);
$page = new IndexPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page index.php");
	recover(0);
}

?>