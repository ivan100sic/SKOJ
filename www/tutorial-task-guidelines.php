<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class TutorialTaskGuidelinesPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
			<h2><a href='tutorials.php'>Tutorials</a> :: Guidelines for problem setting</h2>
			<h3>Naming and language</h3>
			<p>
				Make sure your task's name is short and easy to remember. You are
				encouraged to use any language to write your problems in (e.g. English,
				Serbian), but try to make sure that your task's name is either in the
				same language as the problem statement, or in English. You can use all UTF-8
				characters for both task names and statements.
			</p>
			<h3>More than just plaintext</h3>
			<p>
				You can use the SKOJ markup language to write your task's statement.
				Tags in this language have the following form: \\X for opening tags,
				\\x for closing tags. For more details, check out <a href=''>this</a> guide.
			</p>
			<h3>Test cases</h3>
			<p>
				To verify solutions for your task, you must provide several testcases.
				Check out <a href='tutorial-skoj-lang.php'>this</a> guide to see how 
				the grading system works.
				You should add at least two testcases for each problem. Ideally, your
				testcases should cover all the corner cases of your problem.
			</p>
			<h3>Putting it all together</h3>
			<p>
				You should clearly state all the input/output constraints in your problem.
				Normally, input/output arrays should be zero-indexed, but it is recommended
				that you mention this fact in your task's statement.
			</p>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialTaskGuidelinesPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page tutorial-task-guidelines.php");
	recover(0);
}

?>