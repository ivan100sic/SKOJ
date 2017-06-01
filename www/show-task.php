<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'task.php';
require_once 'submission.php';

class TaskEditPrompt {
	protected $task_id;
	protected $session_id;

	function __construct($task_id, $session_id) {
		$this->task_id = $task_id;
		$this->session_id = $session_id;
	}

	function render($r) {
		if (Task::authorize_edit($this->task_id, $this->session_id)) {
			$r->print("<p><a href='edit-task.php?task_id=$this->task_id'>
				Edit this task</a></p>");
		}
	}
}

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
		$user = User::construct_safe($this->session_id);
		if ($user !== NULL && $user->has_permission("SUBMIT")) {
			$r->print(
			"<div>
				<p>Submit a solution:</p>
				<form action='submit.php' method='POST' enctype='multipart/form-data'>
					<input type='hidden' name='task_id' value='$id'/>
					<input type='file' name='file'/>
					<input type='submit' value='Submit solution'/>
				</form>
				<p>Maximum size: 16384 bytes</p>
			</div>");
		} else if ($user === NULL) {
			$r->print(
			"<div>
				<p><a href='index.php'>Log in</a> to submit!</p>
			</div>");
		} else {
			$r->print(
			"<div>
				<p>You don't have permission to submit. Contact the administrator!</p>
			</div>");
		}
	}
}

class RecentSubmissionsBox {
	protected $user_id;
	protected $task_id;

	function __construct($user_id, $task_id) {
		$this->user_id = $user_id;
		$this->task_id = $task_id;
	}

	function render($r) {
		if ($this->user_id == 0) return;

		$db = SQL::get("
			select *
			from submissions where user_id = ? and task_id = ?
			order by id desc limit 10
		", [$this->user_id, $this->task_id]);

		$r->print("<div><p>Your recent submissions:</p><table>");
		foreach ($db as $row) {
			(new Submission($row))->render_row_simple($r);
		}
		$r->print("</table></div>");
	}
}

class ShowTaskPage extends Page {

	function __construct($task_id, $session_id) {
		parent::__construct();
		$this->task_id = $task_id;
		$this->body_items[] = new TaskEditPrompt($task_id, $session_id);
		$this->body_items[] = new TaskTextBox($task_id);
		$this->body_items[] = new SubmitBox($task_id, $session_id);
		$this->body_items[] =
			new Adapter(Task::construct_safe($task_id), "render_best_solutions");
		$this->body_items[] = new RecentSubmissionsBox($session_id, $task_id);
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