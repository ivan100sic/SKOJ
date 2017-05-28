<?php

require_once 'task.php';
require_once 'global.php';

// var_dump(Task::construct_safe(1));

$type = __post__("type");
$id = __post__("id");

if ($id === NULL) exit();
$task = Task::construct_safe($id);
if ($task === NULL) exit();

switch ($type) {
	case 'name':
		echo $task->get_name();
		exit();
	case 'statement':
		echo $task->get_statement();
		exit();
}


?>