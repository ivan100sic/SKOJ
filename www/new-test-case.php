<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';
require_once 'testcase.php';
require_once 'logger.php';

$task_id = (int)__get__('task_id');
$user_id = get_session_id();

if (Task::authorize_edit($task_id, $user_id)) {
	$db = SQL::run("insert into testcases(name, task_id, source_input, source_output, instruction_limit) values ('', ?, '', '@', 1000)", [$task_id]);

	if (!$db) {
		Logger::critical("Database error on page new-test-case.php");
	}

	$testcase_id = SQL::last_insert_id();
	Testcase::construct_safe($testcase_id)->invalidate();
	Logger::notice("Added new test case $testcase_id for task $task_id");
	header("Location: edit-test-case.php?id=$testcase_id");
} else {
	Logger::notice("Unauthorized access to page new-test-case.php, task_id = $task_id");
	recover(0);
}

?>