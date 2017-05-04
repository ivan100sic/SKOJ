<?php

	class TestRun {
		
		private $submission_id;
		private $testcase_id;
		private $status;
		/*
			Status:
			
			0+ : Accepted (instructions)
			-1 : TLE
			-2 : WA
			-3 : RTE (only division by zero, it seems)
			Anything else?
		*/
		
		function __construct($row) {
			$this->submission_id = $row["submission_id"];
			$this->testcase_id = $row["testcase_id"];
			$this->status = $row["status"];
		}
		
		static function create($submission_id, $testcase_id, $status_string, $instruction_count) {
			$status = 0;
			switch ($status_string) {
				case 'TLE':
					$status = -1; break;
				case 'WA':
					$status = -2; break;
				case 'RTE':
					$status = -3; break;
				case 'AC':
					$status = $instruction_count; break;					
			}

			$db = SQL::run("insert into test_runs(submission_id, testcase_id, status)
				values (?, ?, ?)", [$submission_id, $testcase_id, $status]);
			
			return TestRun::construct_safe($submission_id, $testcase_id);
		}
		
		static function construct_safe($submission_id, $testcase_id) {
			$db = SQL::get("select * from test_runs where submission_id = ? and testcase_id = ?",
				[$submission_id, $testcase_id]);
				
			// This should never happen in TestRun::create
			if (count($db) !== 1) return NULL;
			
			return new TestRun($db[0]);
		}
	}
	?>