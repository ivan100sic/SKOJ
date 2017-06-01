<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';

$statement = __post__('statement');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// No authorization required for this page

if ($statement === NULL) bad_post();

$markup = Markup::convert_to_html($statement);
if ($markup === NULL) {
	// TODO: create an error class
	echo "<span style='color: #ff0000'>Invalid markup!</span>";
	exit();
} else {
	echo $markup;
}

?>