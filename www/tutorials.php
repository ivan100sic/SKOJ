<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'loginbox.php';
require_once 'user.php';

class TutorialsPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
			<p><a href='tutorial-skoj-lang.php'>About the SKOJ language</a></p>
			<p><a href='tutorial-examples.php'>Examples</a></p>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialsPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}