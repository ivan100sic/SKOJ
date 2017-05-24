<?php

require_once 'sql.php';
require_once 'tokenizer.php';
require_once 'syntax-parse.php';
require_once 'testcase.php';
require_once 'grader.php';
require_once 'test_run.php';

class Submission {

	private $id;
	private $user_id;
	private $task_id;
	private $source;
	private $created_on;
	private $status;
	/*
		Status:
		-1 : Not graded
		-2 : Compilation error
		-3 : Not accepted
		0+ : Accepted, overall instruction count
	*/

	const STATUS_NOT_GRADED = -1;
	const STATUS_CE = -2;
	const STATUS_REJECTED = -3;
	
	function __construct($row) {
		$this->id = $row["id"];
		$this->user_id = $row["user_id"];
		$this->task_id = $row["task_id"];
		$this->source = $row["source"];
		$this->created_on = $row["created_on"];
		$this->status = $row["status"];
	}

	function get_id() {
		return $this->id;
	}

	function get_status() {
		return $this->status;
	}
	
	static function construct_safe($id) {
		$db = SQL::get("select * from submissions where id = ?", [$id]);
		if (count($db) !== 1) return NULL;
		return new Submission($db[0]);
	}

	static function create($user_id, $task_id, $source) {
		$db = SQL::run('insert into submissions(user_id, task_id, source, created_on, status) values (?, ?, ?, now(), ?)', 
			[$user_id, $task_id, $source, self::STATUS_NOT_GRADED]);
		if (!$db) {
			return NULL;
		}
		$id = SQL::get('select last_insert_id() as id', []);
		if (count($id) !== 1) return NULL;
		return Submission::construct_safe($id[0]['id']);
	}
	
	private function grade_impl() {
		$db = SQL::get("select * from testcases where task_id = ? order by id asc", [$this->task_id]);			
		$obj = array();
		foreach ($db as $row) {
			// safe
			$obj[] = new Testcase($row);
		}
		
		$source_tokens = Tokenizer::to_token_seq($this->source);
		$source_tree = Program::compile($source_tokens);
		
		// compilation error
		if ($source_tree === NULL) {
			return ["status" => "CE"];
		}
		
		$result = [];
		
		foreach ($obj as $testcase) {
			$result[] = [
				"id"  => $testcase->get_id(),
				"run" => Grader::grade_one($source_tree, $testcase->get_source_input(),
					$testcase->get_source_output(), $testcase->get_instruction_limit())
			];
		}
		
		return ["status" => "OK", "run" => $result];
	}
	
	function save_to_db() {
		SQL::run("update submissions set status = ? where id = ?", [$this->status, $this->id]);
	}
	
	function grade() {
		// create test_run objects and write them to DB
		$grade_result = $this->grade_impl();
		$all_runs = array();
		if ($grade_result["status"] === "CE") {
			// Compilation error, status: -2
			$this->status = self::STATUS_CE;
			$this->save_to_db();
			return;
		}
		
		$total_time = 0;
		$ok = true;
		
		$test_runs = [];
		
		foreach($grade_result["run"] as $tc_result) {
			$run_instructions = 0;
			if ($tc_result["run"]["status"] === "AC") {
				$run_instructions = $tc_result["run"]["instructions"];
				$total_time += $run_instructions;
			} else {
				$ok = false;
			}

			$test_runs[] = TestRun::create($this->id, $tc_result["id"],
				$tc_result["run"]["status"], $run_instructions);
		}
		
		if ($ok) {
			$this->status = $total_time;
		} else {
			$this->status = self::STATUS_REJECTED;
		}
		
		$this->save_to_db();			
	}

	function render_detailed($r) {
		$r->print("<div>");
		if ($this->get_status() == self::STATUS_NOT_GRADED) {
			$r->print("<p>Status: Not graded</p>");
		} else if ($this->get_status() == self::STATUS_CE) {
			$r->print("<p>Status: Compilation error</p>");
		} else {
			if ($this->get_status() == self::STATUS_REJECTED) {
				$r->print("<p>Status: Rejected</p>");
			} else {
				$r->print("<p>Status: Accepted</p>");
				$r->print("<p>Total running time: " . $this->get_status());
			}
			$r->print("<p>Details:</p>");
			$r->print("<table>");
			$test_runs = TestRun::get_all_by_submission_id($this->get_id());
			foreach ($test_runs as $run) {
				$run->render_table_row($r);
			}
			$r->print("</table>");
		}
		$r->print("</div>");
	}
}

?>