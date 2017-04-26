<?php

	require_once('sql.php');
	require_once('tokenizer.php');
	require_once('syntax-parse.php');
	require_once('testcase.php');
	require_once('grader.php');
	require_once('test_run.php');

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
		
		function __construct($row) {
			$this->id = $row["id"];
			$this->user_id = $row["user_id"];
			$this->task_id = $row["task_id"];
			$this->source = $row["source"];
			$this->created_on = $row["created_on"];
			$this->status = $row["status"];
		}
		
		static function construct_safe($id) {
			$db = SQL::get("select * from submissions where id = ?", [$id]);
			if (count($db) !== 1) return NULL;
			return new Submission($db[0]);
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
				$this->status = -2;
				$this->save_to_db();
				return;
			}
			
			$total_time = 0;
			$ok = true;
			
			$test_runs = [];
			
			var_dump($grade_result);
			
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
			
			var_dump($test_runs);
			
			if ($ok) {
				$this->status = $total_time;
			} else {
				$this->status = -3;
			}
			
			$this->save_to_db();			
		}
	}
?>