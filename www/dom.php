<?php

require_once 'global.php';
require_once 'renderer.php';
require_once 'user.php';

class Text {
	private $data;
	
	function __construct($data) {
		$this->data = $data;
	}
	
	function render($r) {
		$r->print($this->data);
	}

	/* Getters, setters... */
}

class EscapedText {
	
	private $data;
	
	function __construct($data) {
		$this->data = $data;
	}
	
	static function convert($data) {
		return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5);
	}
	
	function render($r) {
		$r->print(EscapedText::convert($this->data));
	}

	/* Getters, setters... */
}

class Sidebar {
	function render($r) {
		$r->print("<div class='skoj_sidebar'>
			<p><a href='index.php'>Home</a></p>
			<p><a href='browse-tasks.php'>Browse tasks</a></p>
			<p><a href='hall-of-fame.php'>Hall of Fame</a></p>
			<p><a href=''>My tasks (TODO)</a></p>
			<p><a href='new-task.php'>Add a task</a></p>
			<p><a href='recent-submissions.php'>Recent submissions</a></p>
			<p><a href=''>Tutorials (TODO)</a></p>
			<p><a href='change-password.php'>Change password</a></p>");

		$user = User::construct_safe(get_session_id());

		if ($user !== NULL && $user->has_permission("ADMIN_PANEL")) {
			$r->print("<p><a href='admin-panel.php'>Admin panel</a></p>");
		}

		if ($user !== NULL) {
			$r->print("<p><a href='logout.php'>Log out</a></p>");
		} else {
			$r->print("<p><a href='register.php'>Register</a></p>");
		}

		$r->print("</div>");
	}
}

class Page {
	protected $head_items;
	protected $body_items;
	
	function __construct() {
		$jquery_url = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";
		$this->head_items = [
			"charset" => new Text("<meta charset='UTF-8'/>"),
			"title" => new Text("<title>SKOJ</title>"),
			"jquery" => new Text("<script src='$jquery_url'></script>"),
			"css" => new Text("<link rel='stylesheet' type='text/css' href='skoj.css'/>")
		];
		$this->body_items = [];
	}
	
	function render($r) {
		$r->print("<!DOCTYPE HTML><html><head>");
		foreach ($this->head_items as $key => $value) {
			$value->render($r);
		}
		$r->print("</head><body>");
		// Header div
		$r->print("<div class='skoj_header'>SKOJ</div>");
		(new Sidebar())->render($r);
		$r->print("<div class='skoj_content'>");
		foreach ($this->body_items as $key => $value) {
			$value->render($r);
		}
		$r->print("</div></body></html>");
	}	
}

class Adapter {
	protected $obj;
	protected $method;

	function __construct($obj, $method) {
		$this->obj = $obj;
		$this->method = $method;
	}

	function render($r) {
		$method = $this->method;
		$this->obj->$method($r);
	}
}

?>