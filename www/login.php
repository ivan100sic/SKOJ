<?php

require_once 'global.php';
require_once 'user.php';
require_once 'logger.php';

$username = __post__('username');
$password = __post__('password');

if ($username === NULL || $password === NULL) {
	echo "Bad POST request, hacker! Move on!";
	Logger::notice('Missing username or password in POST on page login.php');
	exit();
}

$user = User::authenticate($username, $password);

if ($user === NULL) {
	Logger::notice('Failed login attempt on page login.php');
	echo "Authentication failed!";
} else {
	echo "OK";
	set_session_id($user->get_id());
	Logger::notice('Successful login attempt on page login.php');
}

?>