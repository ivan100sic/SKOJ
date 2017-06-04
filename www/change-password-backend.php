<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'hash.php';
require_once 'user.php';
require_once 'logger.php';

$id = get_session_id();
$user = User::construct_safe($id);

if ($user == NULL) {
	Logger::notice('Attempted access to change-password-backend.php, bad user id');
	exit();
}

if (__post__("old_password") === NULL) {
	Logger::notice('Attempted access to change-password-backend.php, bad old_password in POST');
	exit(0);
}

if (__post__("password_1") === NULL) {
	Logger::notice('Attempted access to change-password-backend.php, bad password_1 in POST');
	exit(0);
}

if (__post__("password_2") === NULL) {
	Logger::notice('Attempted access to change-password-backend.php, bad password_2 in POST');
	exit(0);
}


$old_password_hash = skoj_hash($user->get_username(), __post__("old_password"));
$password_1 = __post__("password_1");
$password_2 = __post__("password_2");

// Check if the old password is good
$db = SQL::get("select password from users where id = ?", [$id]);
$db_hash = $db[0]['password'];

// Check if the passwords match
if ($old_password_hash != $db_hash) {
	echo "The old password is incorrect!";
	exit();
}

// Check if the passwords match
if ($password_1 != $password_2) {
	echo "The passwords do not match!";
	exit();
}

$db = SQL::run("update users set password = ? where id = ?",
	[skoj_hash($user->get_username(), $password_1), $id]);

Logger::notice("The user changed his/her password");

if ($db) {
	echo "Password changed!";
} else {
	Logger::critical("Database error on change-password-backend.php");
}

?>