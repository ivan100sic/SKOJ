<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'hash.php';
require_once 'user.php';
require_once 'logger.php';

$id = get_session_id();
$user = User::construct_safe($id);
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	Logger::notice('Attempted unauthorized access to edit-user-backend.php');
	exit();
}

if (__post__("id") === NULL) {
	Logger::notice('Attempted access to edit-user-backend.php, bad id in POST');
	exit(0);
}

if (__post__("username") === NULL) {
	Logger::notice('Attempted access to edit-user-backend.php, bad username in POST');
	exit(0);
}

if (__post__("email") === NULL) {
	Logger::notice('Attempted access to edit-user-backend.php, bad email in POST');
	exit(0);
}

if (__post__("password") === NULL) {
	Logger::notice('Attempted access to edit-user-backend.php, bad password in POST');
	exit(0);
}


$username = __post__("username");
$email = __post__("email");
$password = __post__("password");
$id = __post__("id");

if ($password === ""  ){
	$db = SQL::run("update users set username = ?, email = ? where id = ?",
			[$username, $email, $id]);
	Logger::notice("The Admin changed user '$id', details ");
}
else {
	$db = SQL::run("update users set username = ?, email = ?, password = ? where id = ?",
			[$username, $email, skoj_hash($username, $password), $id]);
	Logger::notice("The Admin changed user '$id', details and password ");
}


if ($db) {
	echo "Success";
} else {
	echo "No success";
	Logger::critical("Database error on edit-user-backend.php");
}

?>