<?php

	require_once('sql.php');

	class Testcase {
		
		private $id;
		private $name;
		private $task_id;
		private $source_input;
		private $source_output;
		private $instruction_limit;
		
		function __construct($row) {
			$this->id = $row["id"];
			$this->name = $row["name"];
			$this->task_id = $row["task_id"];
			$this->source_input = $row["source_input"];
			$this->source_output = $row["source_output"];
			$this->instruction_limit = $row["instruction_limit"];
		}
		
		static function construct_safe($id) {
			$db = SQL::get("select * from testcases where id = ?", [$id]);
			if (count($db) !== 1) return NULL;
			return new Testcase($db[0]);
		}
		
		function get_id() {
			return $this->id;
		}
		
		function get_source_input() {
			return $this->source_input;
		}
		
		function get_source_output() {
			return $this->source_output;
		}
		
		function get_instruction_limit() {
			return $this->instruction_limit;
		}
	}