<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'hash.php';

$username = __post__("username");
$email = __post__("email");
$password1 = __post__("password1");
$password2 = __post__("password2");

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

// Create an account
$hash = skoj_hash($password1);

$db = SQL::run("insert into users(username, password, email, created_on)
	values (?, ?, ?, now())", [$username, $password, $email]);

if (!$db) {
	echo "Database error";
} else {
	echo "You have successfully registered! You may now log in!";
}

?>