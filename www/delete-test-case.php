<?php

require_once 'global.php';
require_once 'testcase.php';
require_once 'task.php';
require_once 'sql.php';

$id = __post__('id');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// authorize
if ($id === NULL) bad_post();
$testcase = Testcase::construct_safe($id);
if ($testcase === NULL) bad_post();
$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	bad_post();
}

// Perhaps we just want to delete this test case?
$db = SQL::run("delete from testcases where id = ?",	[$id]);
if ($db) {
	echo "ok";
}

?>