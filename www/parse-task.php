<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';
require_once 'logger.php';

$statement = __post__('statement');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// No authorization required for this page

if ($statement === NULL) {
	Logger::notice("Missing statement in POSt on page parse-task.php");
	bad_post();
}

// No logging needed
$markup = Markup::convert_to_html($statement);
if ($markup === NULL) {
	// TODO: create an error class
	echo "<span class='error'>Invalid markup!</span>";
	exit();
} else {
	echo $markup;
}

?>