<?php

require_once 'global.php';
require_once 'user.php';
require_once 'sql.php';
require_once 'permissions.php';
require_once 'logger.php';

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission('ADMIN_PANEL')) {
	Logger::notice("Unauthorized access to page toggle-perm.php");
	exit();
}

$user = User::construct_safe(__post__('user_id'));
if ($user === NULL) {
	Logger::notice("Bad or missing user_id in POST on page toggle-perm.php");
	exit();
}

$perm_id = __post__('perm_id');
$perm_id = (int)$perm_id;

if (!isset(Permissions::get()[$perm_id])) {
	Logger::notice("Bad or missing perm_id in POST on page toggle-perm.php");
	exit();
}

// Check the current state
$db = SQL::get("select * from users_permissions
	where user_id = ? and permission_id = ?", [$user->get_id(), $perm_id]);

if (count($db) == 1) {
	// Turn off
	SQL::run("delete from users_permissions
	where user_id = ? and permission_id = ?", [$user->get_id(), $perm_id]);
	$x = '.';
} else {
	// Turn on
	SQL::run("insert into users_permissions(user_id, permission_id)
		values (?, ?)", [$user->get_id(), $perm_id]);
	$x = 'X';
}

echo $x;
$u = $user->get_id();
Logger::notice("Flipped permission $perm_id of user $u, is now '$x'");