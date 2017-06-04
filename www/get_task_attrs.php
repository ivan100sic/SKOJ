<?php

require_once 'task.php';
require_once 'global.php';
require_once 'logger.php';

$type = __post__("type");
$id = __post__("id");

if ($id === NULL) {
	Logger::notice("Missing id in POST on get_task_attrs.php");
	exit();
}
$task = Task::construct_safe($id);
if ($task === NULL) {
	Logger::notice("Bad id in POST on get_task_attrs.php");
	exit();
}

switch ($type) {
	case 'name':
		echo $task->get_name();
		exit();
	case 'statement':
		echo $task->get_statement();
		exit();
	case 'default':
		Logger::notice("Bad or missing type in POST on get_task_attrs.php");
		exit();
}


?>