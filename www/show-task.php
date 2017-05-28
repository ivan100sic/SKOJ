<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'task.php';
require_once 'submission.php';

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
				<input type='submit' value='Submit solution'/>
			</form>
			<p>Maximum size: 16384 bytes</p>
		</div>");
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
			select id, created_on, status
			from submissions where user_id = ? and task_id = ?
			limit 10
		", [$this->user_id, $this->task_id]);

		$r->print("<div><p>Your recent submissions:</p><table>");
		foreach ($db as $row) {
			$pretty_status = Submission::status_to_str($row['status']);
			$r->print("<tr>
				<td>${row['id']}</td>
				<td>${row['created_on']}</td>
				<td>$pretty_status</td>
			</tr>");
		}
		$r->print("</table></div>");
	}
}

class ShowTaskPage extends Page {

	function __construct($task_id, $session_id) {
		parent::__construct();
		$this->task_id = $task_id;
		$this->body_items[] = new TaskTextBox($task_id);
		$this->body_items[] = new SubmitBox($task_id, $session_id);
		$this->body_items[] = new Adapter(Task::construct_safe($task_id), "render_best_solutions");
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