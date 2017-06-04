<?php

/* The class which describes a SKOJ hybrid variable */
class SKOJObject {
	private $root;
	private $map;
	
	function __construct() {
		$this->root = NULL;
		$this->map = array();
	}
	
	function get_root() {
		if ($this->root === NULL) {
			$this->root = 0;
		}
		return $this->root;
	}
	
	function set_root($val) {
		$this->root = $val;
	}
	
	function get($idx) {
		if (!isset($this->map[$idx])) {
			$this->map[$idx] = 0;
		}
		return $this->map[$idx];
	}

	function is_dirty_root() {
		return $this->root !== NULL;
	}
	
	function set($idx, $val) {
		$this->map[$idx] = $val;	
	}

	function get_raw() {
		return $this->map;
	}
}

/*
	SKOJ Environment class
*/	
class Environment {
	
	private $map;
	private $instr_count;
	private $max_instr_count;
	private $success;
	
	function __construct($max_instr_count) {
		$this->map = array();
		$this->instr_count = 0;
		$this->max_instr_count = $max_instr_count;
		$this->success = false;
		for ($i = 1; $i <= 26; $i++) {
			$s = chr($i + 96);
			$this->map[$s] = new SKOJObject();
		}
	}
	
	function get_var_value_root($var) {
		return $this->map[$var]->get_root();
	}
	
	function set_var_value_root($var, $val) {
		$this->map[$var]->set_root($val);
	}
	
	function get_var_value($var, $idx) {
		return $this->map[$var]->get($idx);
	}
	
	function set_var_value($var, $idx, $val) {
		$this->map[$var]->set($idx, $val);
	}

	function dink() {
		$this->instr_count++;
		if ($this->instr_count >= $this->max_instr_count) {
			throw new Exception("TLE");
		}
	}
	
	function success() {
		// Poziva se iz testa kada se program uspesno zavrsi
		$this->success = true;
	}
	
	function reset_success() {
		$this->success = false;
	}
	
	function is_successful() {
		return $this->success;
	}
	
	function get_instruction_count() {
		return $this->instr_count;
	}
	
	function set_instruction_count($val) {
		$this->instr_count = $val;
	}
	
	function set_instruction_limit($val) {
		$this->max_instr_count = $val;
	}

	function render($r) {
		$r->print("<h3>Instruction count: $this->instr_count</h3>");
		$succ = $this->success ? "Yes" : "No";
		$r->print("<h3>Cookie statement executed: $succ</h3>");
		$r->print("<h3>Values of variables</h3>");
		for ($i = 1; $i <= 26; $i++) {
			$x = chr($i + 96);
			if ($this->map[$x]->is_dirty_root()) {
				$v = $this->map[$x]->get_root();
				$r->print("<p>$x = $v</p>");
			}
		}
		$r->print("<h3>Values of arrays</h3>");
		for ($i = 1; $i <= 26; $i++) {
			$x = chr($i + 96);
			$raw = $this->map[$x]->get_raw();
			if (count($raw) > 0) {
				ksort($raw);
				$r->print("<h3>$x</h3></p><table class='bordered'>
					<tr><th>Key</th><th>Value</th></tr>");
				foreach ($raw as $key => $value) {
					$r->print("<tr><td class='centered'>$key</td>
						<td class='centered'>$value</td></tr>");
				}
				$r->print("</table>");
			}
		}
	}
}

?>