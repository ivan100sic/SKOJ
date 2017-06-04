<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';
require_once 'logger.php';

$user_id = get_session_id();
$user = User::construct_safe($user_id);

if ($user === NULL || !$user->has_permission('EDIT_OWN_TASKS')) {
	Logger::notice("Unauthorized access to page new-task.php");
	recover(0);
}

$id = Task::create_new($user_id);
Logger::notice("New task created: $id");
header("Location: edit-task.php?task_id=$id");

?>