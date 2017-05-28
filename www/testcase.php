<?php

require_once 'sql.php';

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

	function get_name() {
		return $this->name;
	}

	function get_task_id() {
		return $this->task_id;
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

	function render_edit_row($r) {
		$r->print("
		<tr id='testcase_edit_row_$this->id'>
			<td>$this->name</td>
			<td><a href='edit-test-case.php?id=$this->id' target='_blank'>Edit</a></td>
			<td><a href='javascript:void(0)' onclick='delete_test_case($this->id)'>Delete</a></td>
		</tr>");
	}

	// Remove all test runs for this test case from the database.
	// In addition to this, we need to invalidate statuses for all submissions
	// for problems which contain this testcase
	function invalidate() {
		$db = SQL::run("delete from test_runs where testcase_id = ?", [$this->id]);
		$db = SQL::run("update submissions set status = -1 where task_id = ?", [$this->task_id]);
	}
}

?>