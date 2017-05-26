<?php

	require_once('sql.php');

	class User {
		
		private $id;
		private $username;
		private $password;
		private $email;
		private $created_on;
		private $status;
		
		// additional fields
		private $solved_tasks;
		private $attempted_tasks;
		
		function __construct($row) {
			$this->id = $row["id"];
			$this->username = $row["username"];
			$this->password = $row["password"];
			$this->email = $row["email"];
			$this->created_on = $row["created_on"];
			$this->status = $row["status"];

			$this->solved_tasks = NULL;
			$this->attempted_tasks = NULL;
		}
		
		static function construct_safe($id) {
			
			$db = SQL::get("select * from users where id = ?", [$id]);
			
			// If this is 0, something is wrong
			// If this is neither 0 nor 1, something is TERRIBLY wrong
			if (count($db) !== 1) return NULL;
			
			
			return new User($db[0]);
		}
		
		function render_link() {
			$user_id = $this->id;
			$user_username = $this->username;
			echo "<a href='profile.php?id=$user_id'>$user_username</a>";
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
	}
	
	$user = User::construct_safe(1);
	
	if ($user !== NULL) {
		$user->render_link();
	} else {
		echo "NULL";
	}
	
?>
	