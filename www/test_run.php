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

	const STATUS_TLE = -1;
	const STATUS_WA = -2;
	const STATUS_RTE = -3;
	
	function __construct($row) {
		$this->submission_id = $row["submission_id"];
		$this->testcase_id = $row["testcase_id"];
		$this->status = $row["status"];
	}
	
	static function create($submission_id, $testcase_id, $status_string, $instruction_count) {
		$status = 0;
		switch ($status_string) {
			case 'TLE':
				$status = self::STATUS_TLE; break;
			case 'WA':
				$status = self::STATUS_WA; break;
			case 'RTE':
				$status = self::STATUS_RTE; break;
			case 'AC':
				$status = $instruction_count; break;					
		}

		$db = SQL::run("insert into test_runs(submission_id, testcase_id, status)
			values (?, ?, ?)", [$submission_id, $testcase_id, $status]);
		
		return TestRun::construct_safe($submission_id, $testcase_id);
	}
	
	static function construct_safe($submission_id, $testcase_id) {
		$db = SQL::get("select * from test_runs where submission_id = ? and testcase_id = ?
			order by testcase_id asc",
			[$submission_id, $testcase_id]);
			
		// This should never happen in TestRun::create
		if (count($db) !== 1) return NULL;
		
		return new TestRun($db[0]);
	}

	static function get_all_by_submission_id($submission_id) {
		$db = SQL::get("select * from test_runs where submission_id = ?", [$submission_id]);
		$result = [];
		foreach ($db as $row) {
			$result[] = new TestRun($row);
		}
		return $result;
	}

	function render_table_row($r) {
		$r->temp['rsto'] = 0;

		$r->temp['rsto'] += 1;
		$a = $r->temp['rsto'];
		switch ($this->status) {
			case self::STATUS_TLE:
				$b = 'Time Limit Exceeded'; break;
			case self::STATUS_WA:
				$b = 'Wrong Answer'; break;
			case self::STATUS_RTE:
				$b = 'Runtime Error'; break;
			default:
				$b = 'OK; time = ' . $this->status; break;					
		}

		$r->print("<tr><td>" . $a . "</td><td>" . $b . "</td></tr>");
	}
}

?>