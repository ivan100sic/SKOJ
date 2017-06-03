<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class TutorialsPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
			<h2>Tutorials</h2>
			<h3><a href='tutorial-skoj-lang.php'>About the SKOJ language</a></h3>
			<h3><a href='tutorial-examples.php'>Examples</a></h3>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialsPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page tutorials.php");
	recover(0);
}