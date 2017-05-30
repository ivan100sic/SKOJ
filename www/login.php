<?php

require_once 'global.php';
require_once 'user.php';

$username = __post__('username');
$password = __post__('password');

if ($username === NULL || $password === NULL) {
	echo "Bad POST request, hacker! Move on!";
	exit();
}

$user = User::authenticate($username, $password);

if ($user === NULL) {
	echo "Authentication failed!";
} else {
	echo "OK";
	set_session_id($user->get_id());
}

?>