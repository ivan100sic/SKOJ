<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'hash.php';
require_once 'user.php';

$id = get_session_id();
$user = User::construct_safe($id);

if ($user == NULL) {
	exit();
}

if (__post__("old_password") === NULL) {
	exit(0);
}

if (__post__("password_1") === NULL) {
	exit(0);
}

if (__post__("password_2") === NULL) {
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

if ($db) echo "Password changed!";

?>