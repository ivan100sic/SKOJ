<?php

require_once 'user.php';
require_once 'dom.php';
require_once 'global.php';
require_once 'task.php';

class UserNTask {

	protected $user;
	protected $task;

	function __construct($user, $task) {
		$this->user = $user;
		$this->task = $task;
	}

	function render($r) {
		$uid = $this->user->get_id();
		$tid = $this->task->get_id();
		$r->print("<a href=
			'user-task-submissions.php?user_id=$uid&task_id=$tid'>");
		(new EscapedText($this->task->get_name()))->render($r);
		$r->print("</a>");
	}
}

class ProfileBox {

	protected $id;

	function __construct($id) {
		$this->id = $id;
	}

	function render($r) {
		$user = User::construct_safe($this->id);
		$r->print("<p>Solved tasks:</p><div>");
		$comma = '';
		foreach ($user->get_solved_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			(new UserNTask($user, $task))->render($r);
		}
		$r->print("</div><p>Attempted tasks:</p><div>");
		$comma = '';
		foreach ($user->get_attempted_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			(new UserNTask($user, $task))->render($r);
		}
		$comma = '';
		$r->print("</div><p>Authored tasks:</p><div>");
		foreach ($user->get_authored_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			$task->render_link($r);
		}
	}
}

class ProfilePage extends Page {

	function __construct($id) {
		parent::__construct();
		$this->body_items[] = new ProfileBox($id);
	}
}

$id = __get__('id');

$r = new Renderer(0);
$page = new ProfilePage($id);
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}
