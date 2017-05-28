<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';

$task_id = (int)__get__('task_id');
$user_id = get_session_id();

if (Task::authorize_edit($task_id, $user_id)) {
	$db = SQL::run("insert into testcases(name, task_id, source_input, source_output, instruction_limit) values ('', ?, '', '@', 1000)", [$task_id]);

	$testcase_id = SQL::last_insert_id();
	header("Location: edit-test-case.php?testcase_id=$id");
} else {
	recover(0);
}

?>