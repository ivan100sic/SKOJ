<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'hash.php';
require_once 'logger.php';

$username = __post__("username");
$email = __post__("email");
$password1 = __post__("password1");
$password2 = __post__("password2");

Logger::notice("New registration attempt");

// Check if username is not empty
if ($username === "" || $username == NULL) {
	echo "Username cannot be empty!";
	exit();
}

// Check if the username is available
$db = SQL::get("select * from users where username = ?", [$username]);
if (count($db) == 1) {
	echo "This username is taken!";
	exit();
}

// Check if the email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	echo "Invalid email!";
	exit();
}

// Check if there's an account with this email
$db = SQL::get("select * from users where email = ?", [$email]);
if (count($db) == 1) {
	echo "This email address has already been registered!";
	exit();
}

// Check if the passwords match
if ($password1 != $password2) {
	echo "The passwords do not match!";
	exit();
}

Logger::notice("The registration attempt is valid");

// Create an account
$hash = skoj_hash($username, $password1);

$db = SQL::run("insert into users(username, password, email, created_on)
	values (?, ?, ?, now())", [$username, $hash, $email]);

if (!$db) {
	Logger::critical("Database error on first insert on page register_backend.php");
	echo "Database error, account not created";
}

$id = SQL::last_insert_id();

// Add some permissions
$db = SQL::run("insert into users_permissions(user_id, permission_id) values
	(?, 1), (?, 2), (?, 3)", [$id, $id, $id]);

if (!$db) {
	Logger::critical("Database error on second insert on page register_backend.php");
	echo "Database error, your account may be in an invalid state";
}

echo "You have successfully registered! You may now log in!";
Logger::notice("Successfully completed the registration");

?>