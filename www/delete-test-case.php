<?php

require_once 'global.php';
require_once 'testcase.php';
require_once 'task.php';
require_once 'sql.php';
require_once 'logger.php';

$id = __post__('id');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// authorize
if ($id === NULL) {
	Logger::notice("Missing testcase id in POST on page delete-test-case.php");
	bad_post();
}

$testcase = Testcase::construct_safe($id);
if ($testcase === NULL) {
	Logger::notice("Bad testcase id in POST on page delete-test-case.php");
	bad_post();
}
$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	Logger::notice("User not authorized to edit task on page delete-test-case.php");
	bad_post();
}

$testcase->invalidate();

// Delete this test case
$db = SQL::run("delete from testcases where id = ?", [$id]);
if ($db) {
	echo "ok";
} else {
	Logger::critical("Database error on page delete-test-case.php");
}

?>