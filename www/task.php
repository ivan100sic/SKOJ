<?php

	require_once('sql.php');
	
	class Task {
		
		private $id;	
		private $name;	
		private $statement;	
		private $author;	
		private $created_on;	
		private $status;
		
		function __construct($row) {
			$this->id = $row["id"];
			$this->name = $row["name"];
			$this->statement = $row["statement"];
			$this->author = $row["author"];
			$this->created_on = $row["created_on"];
			$this->status = $row["status"];
		}
		
		static function construct_safe($id) {
			
			$db = SQL::get("select * from tasks where id = ?", [$id]);
			
			if (count($db) == 0) return NULL;
			return new Task($db[0]);
		}
		
		function render_statement() {
			echo Markup::convert_to_html($this->statement);
		}
	}
	
?>