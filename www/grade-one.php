<?php

// Testing
require_once 'sql.php';
require_once 'submission.php';

$db = SQL::get("select * from submissions where status = -1 order by created_on limit 1", []);
if (count($db) > 0) {
	$submission = new Submission($db[0]);
	$submission->grade();
	$id = $submission->get_id();
	echo "<p>Graded submission <a href='show-submission.php?id=$id'>$id</a></p>";
} else {
	echo "<p>No more submissions to grade!</p>";
}
	
?>