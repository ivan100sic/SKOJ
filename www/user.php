<?php

	require_once('sql.php');

	class User {
		
		private $id;
		private $username;
		private $password;
		private $email;
		private $created_on;
		private $status;
		
		function __construct($row) {
			$this->id = $row["id"];
			$this->username = $row["username"];
			$this->password = $row["password"];
			$this->email = $row["email"];
			$this->created_on = $row["created_on"];
			$this->status = $row["status"];
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
	}
	
	$user = User::construct_safe(1);
	
	if ($user !== NULL) {
		$user->render_link();
	} else {
		echo "NULL";
	}
	
?>
	