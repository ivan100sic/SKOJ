<?php
	
require_once 'global.php';
require_once 'dom.php';
require_once 'user.php';
require_once 'paginate.php';
require_once 'logger.php';

class MyTasksPage extends Page {

	function __construct($user_id) {
		parent::__construct();
		$user = User::construct_safe($user_id);
		if ($user !== NULL && $user->has_permission("EDIT_OWN_TASKS")) {
			$this->body_items[] = new Text("<h2>Your tasks</h2>");
			$this->body_items[] = new Text(
				"<p><a href='new-task.php'>Add a task</a></p>
				<input type='hidden' id='user_tasks_author' value='$user_id'/>");
			// This does not prevent users from sending a POST request to see other people's
			// authored tasks in a paginated view. However, this is not a security issue,
			// as they still can't edit them.
			$this->body_items[] = new PaginateFrontend(PaginateTypes::get('user_tasks'));
		} else {
			recover(0);
		}
	}
}

$r = new Renderer(0);
if (get_session_id() === 0) {
	Logger::notice("Attempted access to page my-tasks.php");
	recover(0);
}
$page = new MyTasksPage(get_session_id());
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page my-tasks.php");
	recover(0);
}

?>