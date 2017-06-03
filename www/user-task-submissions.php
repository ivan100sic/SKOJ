<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'paginate.php';
require_once 'user.php';
require_once 'task.php';
require_once 'logger.php';

class UserTaskSubmissionsPage extends Page {

	function __construct($user_id, $task_id) {
		parent::__construct();
		$this->body_items[] = new Text("
			<input type='hidden' id='user_task_subs_user_id' value='$user_id'/>
			<input type='hidden' id='user_task_subs_task_id' value='$task_id'/>
		");
		$this->body_items[] = new Text("<h3>Task: ");
		$this->body_items[] = new Adapter(Task::construct_safe($task_id), "render_link");
		$this->body_items[] = new Text("</h3><h3>Submissions by: ");
		$this->body_items[] = new Adapter(User::construct_safe($user_id), "render_link");
		$this->body_items[] = new Text("</h3>");
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('user_task_subs'));
	}
}

$user_id = __get__('user_id');
$task_id = __get__('task_id');

if (User::construct_safe($user_id) === NULL) {
	Logger::notice("Bad user_id in GET on page user-task-submissions.php");
	recover(0);
}

if (Task::construct_safe($task_id) === NULL) {
	Logger::notice("Bad task_id in GET on page user-task-submissions.php");
	recover(0);
}

$r = new Renderer(0);
$page = new UserTaskSubmissionsPage($user_id, $task_id);
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page user-task-submissions.php");
	recover(0);
}

?>