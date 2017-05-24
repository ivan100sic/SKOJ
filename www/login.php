<?php

require_once 'global.php';
require_once 'user.php';

$username = __post__('username');
$password = __post__('password');

$user = User::authenticate($username, $password);

if ($user === NULL) {
	// Login failed
} else {
	set_session_id($user->get_id());
}

?>