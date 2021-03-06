<?php
// Tokenizer - Parses the given string and forms an instance of TokenSequence koja od datog

require_once 'token-seq.php';

class Tokenizer {
	
	static function is_digit($s) {
		return preg_match("/^[0-9]$/", $s) === 1;
	}
	
	static function is_variable($s) {
		return preg_match("/^[a-z]$/", $s) === 1;
	}
	
	static function to_token_seq($s) {
		
		$tokens_raw = [
			/* Two-character operators, which are given higher priority */
		
			"==", "!=", ">=", "<=",
			"&&", "||", "<<", ">>",
			
			/* One-character operators */
			
			"`", "!", "~",
			"+", "-", "*", "/", "%",
			">", "<", "&", "|", "^",
			
			/* Other tokens */
			
			"@", "=", ";", ".",
			"[", "]", "{", "}"
		];
		
		$tokens_names = [
			
			"equal", "not_equal", "greater_or_equal", "less_or_equal",
			"logical_and", "logical_or", "shift_left", "shift_right",
			
			"unary_minus", "not", "complement",
			"plus", "minus", "times", "divide", "mod",
			"greater", "less", "bitwise_and", "bitwise_or", "bitwise_xor",
			
			"cookie", "assignment", "semicolon", "dot",
			"left_bracket", "right_bracket", "left_brace", "right_brace"
		];

		$k = count($tokens_raw);
		
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