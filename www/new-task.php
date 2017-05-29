<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';

$user_id = get_session_id();
$user = User::construct_safe($user_id);

if ($user === NULL || !$user->has_permission('EDIT_OWN_TASKS')) {
	recover(0);
}

$id = Task::create_new($user_id);
header("Location: edit-task.php?task_id=$id");

?>