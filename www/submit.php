<?php

// Accept a POST Request and store the submission
// into the database

require_once 'global.php';
require_once 'submission.php';
require_once 'task.php';
require_once 'logger.php';

$task_id = __post__('task_id');
$file = __files__('file');
if ($file === NULL) {
	$contents = '';
} else {
	$contents = file_get_contents($file['tmp_name']);
}

$user_id = get_session_id();
if ($user_id === 0) {
	Logger::notice('Attepted submit by guest user');
	recover(0);
}

if (Task::construct_safe($task_id) === NULL) {
	Logger::notice('Bad task_id in POST on submit.php');
	recover(0);
}

$submission = Submission::create($user_id, $task_id, $contents);
if ($submission === NULL) {
	Logger::critical("Database error on submit.php");
	recover(0);
} else {
	$submission->grade();
	$id = $submission->get_id();
	Logger::notice('Successful submission, id = $id');
	header('Location: show-submission.php?id=$id');
}

?>