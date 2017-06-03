<?php

require_once 'global.php';
require_once 'task.php';
require_once 'testcase.php';
require_once 'logger.php';

$type = __post__('type');
$testcase_id = __post__('id');
if ($testcase_id === NULL) {
	Logger::notice('Missing id in POST on page get_testcase_attrs.php');
	exit(0);
}

$testcase = Testcase::construct_safe($testcase_id);
if ($testcase === NULL) {
	Logger::notice('Bad id in POST on page get_testcase_attrs.php');
	exit(0);
}

$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	Logger::notice('User not authorized to edit task on page get_testcase_attrs.php');
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
	case 'default':
		Logger::notice("Bad or missing type in POST on get_testcase_attrs.php");
		exit();
}

?>