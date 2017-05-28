<?php

require_once 'global.php';
require_once 'task.php';
require_once 'testcase.php';

$type = __post__('type');
$testcase_id = __post__('id');
if ($testcase_id === NULL) exit(0);
$testcase = Testcase::construct_safe($testcase_id);
if ($testcase === NULL) exit(0);

$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	exit();
}

switch ($type) {
	case 'name':
		echo $testcase->get_name();
		exit();
	case 'source_input':
		echo $testcase->get_source_input();
		exit();
	case 'source_output':
		echo $testcase->get_source_output();
		exit();
	case 'instruction_limit':
		echo $testcase->get_instruction_limit();
		exit();
}

?>