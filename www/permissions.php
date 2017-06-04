<?php

class Permissions {
	function get() {
		return [
			1 => 'LOGIN',
			2 => 'SUBMIT',
			3 => 'EDIT_OWN_TASKS',
			4 => 'PUBLISH_TASKS',
			5 => 'EDIT_ALL_TASKS',
			6 => 'ADMIN_PANEL'
		];
	}

	function get_header() {
		return "";
	}
}

?>