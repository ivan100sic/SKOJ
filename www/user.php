<?php

require_once 'sql.php';
require_once 'hash.php';

class User {
	
	private $id;
	private $username;
	private $password;
	private $email;
	private $created_on;

	// Permissions are integrated into the User class
	// Array of permission names, as strings
	private $permissions;
	
	// additional fields
	private $solved_tasks;
	private $attempted_tasks;

	function get_id() {
		return $this->id;
	}
	
	function __construct($row) {
		$this->id = $row["id"];
		$this->username = $row["username"];
		$this->password = $row["password"];
		$this->email = $row["email"];
		$this->created_on = $row["created_on"];
		
		// Permissions
		$db = SQL::get("select * from users_permissions inner join permissions
			on users_permissions.permission_id = permissions.id where user_id = ?",
			[$row['id']]);

		$this->permissions = [];
		foreach ($db as $pr) {
			$this->permissions[] = $pr['name'];
		}

		// Temporaries
		$this->solved_tasks = NULL;
		$this->attempted_tasks = NULL;
	}

	function has_permission($perm) {
		foreach ($this->permissions as $p) {
			if ($p === $perm) {
				return true;
			}
		}
		return false;
	}
	
	static function construct_safe($id) {
		
		$db = SQL::get("select * from users where id = ?", [$id]);
		if (count($db) !== 1) return NULL;

		$row = $db[0];
		$db = SQL::get("select * from users_permissions inner join permissions
			on users_permissions.permission_id = permissions.id where user_id = ?",
			[$row['id']]);
		$perms = [];
		foreach ($db as $pr) {
			$perms[] = $pr['name'];
		}		
		return new User($row, $perms);
	}
	
	function render_link($r) {
		$user_id = $this->id;
		$user_username = $this->username;
		$r->print("<a href='profile.php?id=$user_id'>$user_username</a>");
	}
	
	function get_solved_tasks() {
		if ($this->solved_tasks !== NULL) {
			return $this->solved_tasks;
		}
		$a = [];
		$db = SQL::get("select * from tasks t1 where
			(select count(*) from submissions where
				user_id = ? and
				task_id = t1.id and
				status >= 0
			) > 0", [$this->id]);
			
		foreach ($db as $row) {
			$a[] = new Task($row);
		}
		return $this->solved_tasks = $a;
	}
	
	function get_attempted_tasks() {
		if ($this->attempted_tasks !== NULL) {
			return $this->attempted_tasks;
		}
		$a = [];
		/* Without successful submissions but with some attempts */
		$db = SQL::get("select * from tasks t1 where
			(select count(*) from submissions where
				user_id = ? and
				task_id = t1.id and
				status >= 0
			) = 0 and
			(select count(*) from submissions where
				user_id = ? and
				task_id = t1.id
			) > 0", [$this->id, $this->id]);
			
		foreach ($db as $row) {
			$a[] = new Task($row);
		}
		return $this->attempted_tasks = $a;
	}

	static function authenticate($username, $password) {
		$hashed = skoj_hash($username, $password);
		$db = SQL::get("select * from users where username = ? and password = ?",
			[$username, $hashed]);
		if (count($db) !== 1) {
			return NULL;
		}
		return User::construct_safe($db[0]['id']);
	}

	function render_row_simple($r) {
		$r->print("<tr><td>$this->username</td></tr>");
	}
}


?>
	