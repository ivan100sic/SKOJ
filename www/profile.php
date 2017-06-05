<?php

require_once 'user.php';
require_once 'dom.php';
require_once 'global.php';
require_once 'task.php';
require_once 'logger.php';
require_once 'paginate.php';

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
		$r->print("<h2>User: ");
		(new EscapedText($user->get_username()))->render($r);
		$r->print("</h2>");
		$r->print("<h3>Solved tasks:</h3><div class='vspace'>");
		$comma = '';
		foreach ($user->get_solved_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			(new UserNTask($user, $task))->render($r);
		}
		$r->print("</div><h3>Attempted tasks:</h3><div class='vspace'>");
		$comma = '';
		foreach ($user->get_attempted_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			(new UserNTask($user, $task))->render($r);
		}
		$comma = '';
		$r->print("</div><h3>Authored tasks:</h3><div class='vspace'>");
		foreach ($user->get_authored_tasks() as $task) {
			$r->print($comma);
			$comma = ", ";
			$task->render_link($r);
		}
		$r->print("</div><h3>Recent submissions:</h3><div>");
		(new PaginateFrontend(PaginateTypes::get('user_subs')))->render($r);
		$r->print("</div>");
	}
}

class ProfilePage extends Page {

	function __construct($id) {
		parent::__construct();
		$this->body_items[] = new Text("
			<input type='hidden' id='user_subs_user_id' value='$id'/>");
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
	Logger::error("Exception occurred on page profile.php");
	recover(0);
}
