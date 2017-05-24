<?php

// Accept a POST Request and store the submission
// into the database

require_once 'global.php';
require_once 'submission.php';

$task_id = __post__('task_id');
$file = __files__('file');
if ($file === NULL) {
	$contents = '';
} else {
	$contents = file_get_contents($file['tmp_name']);
}

$user_id = get_session_id();
$submission = Submission::create($user_id, $task_id, $contents);
if ($submission === NULL) {
	recover(0);
} else {
	$submission->grade();
	header('Location: show-submission.php?id=' . $submission->get_id());
}

?>