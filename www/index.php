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
			$this->body_items[] = new Text("</p>");
			$this->body_items[] = new Text("<p><a href='logout.php'>Log out</a></p>");
			$this->body_items[] = new Text("<p><a href='browse-tasks.php'>Browse tasks</a></p>");
			$this->body_items[] = new Text("<p><a target='_blank' href='new-task.php'>Create a new task</a></p>");
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