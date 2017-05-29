<?php

require_once 'global.php';
require_once 'user.php';

$username = __post__('username');
$password = __post__('password');

$user = User::authenticate($username, $password);

if ($user === NULL || $password === NULL) {
	recover(0);
}

if ($user === NULL) {
	echo "Authentication failed!";
} else {
	echo "OK";
	set_session_id($user->get_id());
}

?>