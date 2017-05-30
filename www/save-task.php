<?php

require_once 'global.php';
require_once 'task.php';
require_once 'sql.php';

$id = __post__('id');
$name = __post__('name');
$statement = __post__('statement');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// authorize
if ($id === NULL) bad_post();
if ($name === NULL) bad_post();
if ($statement === NULL) bad_post();

if (!Task::authorize_edit($id, get_session_id())) {
	bad_post();
}

if ($name === "") {
	echo "Task name cannot be empty!";
	exit();
}

if ($statement === "") {
	echo "Statement cannot be empty!";
	exit();
}

$markup = Markup::convert_to_html($statement);
if ($markup === NULL) {
	echo "Invalid markup!";
	exit();
}

$db = SQL::run("update tasks set
	name = ?,
	statement = ?
	where id = ?",
	[$name, $statement, $id]
);

if (!$db) {
	echo "Database error!";
} else {
	echo "Changes saved!";
}

?>