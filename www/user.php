<?php

require_once 'sql.php';
require_once 'hash.php';
require_once 'permissions.php';

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
	private $authored_tasks;

	function get_id() {
		return $this->id;
	}

	function get_username() {
		return $this->username;
	}
	
	function get_email() {
		return $this->email;
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
		$this->authored_tasks = NULL;
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
		$r->print("<a href='profile.php?id=$this->id'>");
		(new EscapedText($this->username))->render($r);
		$r->print("</a>");
	}
	
	function get_solved_tasks() {
		if ($this->solved_tasks !== NULL) {
			return $this->solved_tasks;
		}
		$a = [];
		$db = SQL::get("select * from solved inner join tasks
			on task_id = tasks.id where user_id = ?", [$this->id]);
			
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
		$db = SQL::get("select * from attempted inner join tasks
			on task_id = tasks.id where user_id = ?", [$this->id]);
			
		foreach ($db as $row) {
			$a[] = new Task($row);
		}
		return $this->attempted_tasks = $a;
	}

	function get_authored_tasks() {
		if ($this->authored_tasks !== NULL) {
			return $this->authored_tasks;
		}
		$a = [];
		$db = SQL::get("select * from tasks where author = ?", [$this->id]);
		foreach ($db as $row) {
			$a[] = new Task($row);
		}
		return $this->authored_tasks = $a;
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

	function render_row_hall_of_fame($r) {
		$sp = $r->temp['user_solved_problems'];
		if ($sp == 1) {
			$suffix = '';
		} else {
			$suffix = 's';
		}
		$r->print("<tr><td>");
		$this->render_link($r);
		$r->print("</td>
			<td>$sp problem$suffix solved</td>
		</tr>");
	}

	function render_row_edit_perms($r) {
		$r->print("<tr id='edit_perms_$this->id'><td>");
		$this->render_link($r);
		$r->print("</td><td><a href='edit-user?user_id=$this->id'>Edit</a></td>");
		foreach (Permissions::get() as $perm_id => $perm_name) {
			$has = $this->has_permission($perm_name) ? 'X' : '.';
			$r->print("<td class='centered'><a
				href='javascript:toggle_perm(
					$this->id, $perm_id
				)'
				id = 'edit_perms_link_".$this->id."_$perm_id'>$has</a></td>");
		}
		$r->print('</tr>');
	}
}


?>