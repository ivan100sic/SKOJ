<?php 
require_once 'user.php';
require_once 'global.php';
require_once 'logger.php';

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	Logger::notice('Attempted unauthorized access to get_user_attrs.php');
	exit();
}

$type = __post__("type");
$id = __post__("id");

if ($id === NULL) {
	Logger::notice("Missing id in POST on get_user_attrs.php");
	exit();
}
$user = User::construct_safe($id);
if ($user === NULL) {
	Logger::notice("Bad id in POST on get_user_attrs.php");
	exit();
}

switch ($type) {
	case 'username':
		echo $user->get_username();
		exit();
	case 'email':
		echo $user->get_email();
		exit();
	default:
		Logger::notice("Bad or missing type in POST on get_user_attrs.php");
		exit();
}

?>