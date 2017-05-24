<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'task.php';

class TaskTextBox {

	protected $task_id;

	function __construct($task_id) {
		$this->task_id = $task_id;
	}

	function render($r) {
		$task = Task::construct_safe($this->task_id);
		if ($task === NULL) {
			throw new Exception("GET ERROR");
		} else {
			$task->render_statement($r);
		}
	}
}

class SubmitBox {
	protected $task_id;
	protected $session_id;

	function __construct($task_id, $session_id) {
		$this->task_id = $task_id;
		$this->session_id = $session_id;
	}

	function render($r) {
		$id = $this->task_id;
		$r->print(
		"<div>
			<p>Submit a solution:</p>
			<form action='submit.php' method='POST' enctype='multipart/form-data'>
				<input type='hidden' name='task_id' value='$id'/>
				<input type='file' name='file'/>
				<input type='submit'/>
			</form>
			<p>Maximum size: 16384 bytes</p>
		</div>");
	}
}

class ShowTaskPage extends Page {

	protected $task_id;

	function __construct($task_id, $session_id) {
		parent::__construct();
		$this->task_id = $task_id;
		$this->body_items[] = new TaskTextBox($task_id);
		$this->body_items[] = new SubmitBox($task_id, $session_id);
	}
}

// $session_id = $_SESSION["id"];
$session_id = get_session_id();
$task_id = (int)__get__('task_id');

try {
	$r = new Renderer(0);
	$page = new ShowTaskPage($task_id, $session_id);
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover($e->getMessage());
}

?>