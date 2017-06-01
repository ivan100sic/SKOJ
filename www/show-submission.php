<?php

require_once 'global.php';
require_once 'submission.php';
require_once 'dom.php';

class ShowSubmissionPage extends Page {

	function __construct($submission) {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Submission details</h2>");
		$this->body_items[] = new Adapter($submission, "render_detailed");
	}
}

$submission_id = __get__('id');
$submission = Submission::construct_safe($submission_id);

if ($submission === NULL) {
	recover(0);
}

$r = new Renderer(0);
$page = new ShowSubmissionPage($submission);
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>