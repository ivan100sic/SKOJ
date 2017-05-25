<?php

session_start();

require_once 'user.php';

function __get__($id) {
	if (isset($_GET[$id])) {
		return $_GET[$id];
	}
	return NULL;
}

function __post__($id) {
	if (isset($_POST[$id])) {
		return $_POST[$id];
	}
	return NULL;
}

function get_session_id() {
	if (isset($_SESSION['id'])) {
		$id = $_SESSION['id'];
		$user = User::construct_safe($id);
		if ($user !== NULL && $user->has_permission("LOGIN")) {
			return $id;
		} else {
			unset($_SESSION['id']);
			return 0;
		}
	}
	return 0;
}

function set_session_id($id) {
	$_SESSION['id'] = $id;
}

function __files__($id) {
	if (isset($_FILES[$id])) {
		return $_FILES[$id];
	}
	return NULL;
}

// Recover from an error. For now, it redirects
// to index.php. Perhaps I should show some error page?
function recover($params) {
	header('Location: index.php');
	exit();
}

?>