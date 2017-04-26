<?php

	/* Klasa koja opisuje jedan SKOJ objekat. */
	class SKOJObject {
		private $root;
		private $map;
		
		function __construct() {
			$this->root = 0;
			$this->map = array();
		}
		
		function get_root() {
			return $this->root;
		}
		
		function set_root($val) {
			$this->root = $val;
		}
		
		// TODO ovo ZAISTA mora da se istestira dobro sa velikim indeksima
		function get($idx) {
			if (!isset($this->map[$idx])) {
				$this->map[$idx] = 0;
			}
			return $this->map[$idx];
		}
		
		function set($idx, $val) {
			$this->map[$idx] = $val;	
		}
	}

	/*
		Klasa koja opisuje stanje memorije celokupnog runtime okruzenja
		SKOJ-a i takodje sluzi za brojanje izvrsenih instrukcija.
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
		
	}