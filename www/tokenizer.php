<?php
	/*
		Tokenizer - klasa koja od datog stringa formira niz tokena
		odnosno instancu klase TokenSequence.
	*/
	
	require_once('token-seq.php');
	
	class Tokenizer {
		
		static function is_digit($s) {
			return preg_match("/^[0-9]$/", $s) === 1;
		}
		
		static function is_variable($s) {
			return preg_match("/^[a-z]$/", $s) === 1;
		}
		
		static function to_token_seq($s) {
			
			$k = 28;
			
			$tokens_raw = [
				/* Operatori od dva znaka, daje im se visi prioritet */
			
				"==", "!=", ">=", "<=",
				"&&", "||", "<<", ">>",
				
				/* Operatori od jednog znaka (svi ostali) */
				
				"`", "!", "~",
				"+", "-", "*", "/", "%",
				">", "<", "&", "|",
				
				/* Ostali jezicki tokeni */
				
				"@", "=", ";", ".",
				"[", "]", "{", "}"
			];
			
			$tokens_names = [
				
				"equal", "not_equal", "greater_or_equal", "less_or_equal",
				"logical_and", "logical_or", "shift_left", "shift_right",
				
				"unary_minus", "not", "complement",
				"plus", "minus", "times", "divide", "mod",
				"greater", "less", "logical_and", "logical_or",
				
				"cookie", "assignment", "semicolon", "dot",
				"left_bracket", "right_bracket", "left_brace", "right_brace"
			];
			
			$n = strlen($s);
			$s .= "#";
			$i = 0;
			
			$a = new TokenSequence();
			
			while ($i < $n) {
				if (Tokenizer::is_digit($s[$i])) {
					/* scan an integer literal */
					$start_idx = $i;
					while (Tokenizer::is_digit($s[$i])) {
						$i++;
					}
					$num = substr($s, $start_idx, $i - $start_idx);
					$a->append($num);
				} else if (Tokenizer::is_variable($s[$i])) {
					/* variable */
					$a->append($s[$i]);	
					$i++;
				} else {
					/* operator or special char */
					$skip = true;
					for ($j = 0; $j < $k; $j++) {
						$needle = $tokens_raw[$j];
						$candidate = substr($s, $i, strlen($needle));
						$name = $tokens_names[$j];
						
						if ($needle === $candidate) {
							$a->append($name);
							$i += strlen($needle);
							$skip = false;
							break;
						}
					}
					if ($skip) $i++;
				}
			}
			
			return $a;
		}		
	}
?>