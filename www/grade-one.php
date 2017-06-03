<?php

require_once 'sql.php';
require_once 'submission.php';
require_once 'logger.php';

$db = SQL::get("select * from submissions where status = -1 order by id limit 1", []);
if (count($db) > 0) {
	$submission = new Submission($db[0]);
	$submission->grade();
	$id = $submission->get_id();
	echo "<p>Graded submission <a href='show-submission.php?id=$id'>$id</a></p>";
	Logger::notice("Regraded submission $id on page grade-one.php");
} else {
	echo "<p>No more submissions to grade!</p>";
	Logger::notice("No more submissions to regrade on page grade-one.php");
}
	
?>