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
			if (!isset($map[$idx])) {
				$map[$idx] = 0;
			}
			return $map[$idx];
		}
		
		function set($idx, $val) {
			$map[$idx] = $val;	
		}
	}

	/*
		Klasa koja opisuje stanje memorije celokupnog runtime okruzenja
		SKOJ-a i takodje sluzi za brojanje izvrsenih instrukcija.
	*/	
	class Environment {
		
		private $map;
		private $instr_count;
		
		function __construct($max_instr_count) {
			$this->map = array();
			$this->nstr_count = 0;
			$this->max_instr_count = $max_instr_count;
			for ($i = 1; $i <= 26; $i++) {
				$s = chr($i);
				$map[$s] = new SKOJObject();
			}
		}
		
		function get_var_value_root($var) {
			return $map[$var]->get_root();
		}
		
		function set_var_value_root($var, $val) {
			$map[$var]->set_root($val);
		}
		
		function get_var_value($var, $idx) {
			return $map[$var]->get($idx);
		}
		
		function set_var_value($var, $idx, $val) {
			$map[$var]->set($idx, $val);
		}

		function dink() {
			$instr_count++;
			if ($instr_count == $max_instr_count) {
				throw new Exception("TLE");
			}
		}
		
	}