<?php

require_once 'global.php';
require_once 'user.php';
require_once 'sql.php';
require_once 'permissions.php';

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission('ADMIN_PANEL')) {
	exit();
}

$user = User::construct_safe(__post__('user_id'));
if ($user === NULL) {
	exit();
}

$perm_id = __post__('perm_id');
$perm_id = (int)$perm_id;

if (!isset(Permissions::get()[$perm_id])) {
	exit();
}

// Check the current state
$db = SQL::get("select * from users_permissions
	where user_id = ? and permission_id = ?", [$user->get_id(), $perm_id]);

if (count($db) == 1) {
	// Turn off
	SQL::run("delete from users_permissions
	where user_id = ? and permission_id = ?", [$user->get_id(), $perm_id]);
	echo ".";
} else {
	// Turn on
	SQL::run("insert into users_permissions(user_id, permission_id)
		values (?, ?)", [$user->get_id(), $perm_id]);
	echo "X";
}
