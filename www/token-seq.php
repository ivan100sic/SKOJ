<?php
	/*
		Klasa koja predstavlja niz tokena ulaznog programa. Koriste
		je syntax-parse (cita iz nje) a konstruise je tokenizer.
	*/
	
	class TokenSequence {
		private $token_seq;
		private $size;
		
		function __construct() {
			$this->token_seq = array();
			$this->size = 0;
		}

		function append($token) {
			$token_seq[] = $token;
			$this->size++;
		}
		
		function get($n) {
			if ($n < 0 || $n >= $this->size) {
				return "invalid";
			}
			return $token_seq[$n];
		}		
	}
?>
