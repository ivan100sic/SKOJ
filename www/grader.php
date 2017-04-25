<?php

	require_once('syntax-parse.php');
	require_once('tokenizer.php');

	class Grader {
		
		static function grade_one($source_tree, $input, $output, $instruction_limit) {
			$input_tokens = Tokenizer::to_token_seq($input);
			$output_tokens = Tokenizer::to_token_seq($output);
			
			$input_tree = Program::parse($input_tokens, 0);
			$output_tree = Program::parse($output_tokens, 0);
			
			$renv = new Environment(16000);
			
			try {
				$input_tree->run($renv);
				$input_instructions = $renv->get_instruction_count();
				
				$renv->set_instruction_count(0);
				$renv->set_instruction_limit($instruction_limit);
				$source_tree->run($renv);
				$source_instructions = $renv->get_instruction_count();
				
				$renv->set_instruction_count(0);
				$renv->set_instruction_limit(16000);
				$output_tree->run($renv);
				$output_instructions = $renv->get_instruction_count();
				
				$result = [];
				
				if ($renv->is_successful()) {
					$result["status"] = "AC";
				} else {
					$result["status"] = "WA";
				}
				
				// For diagnostic purposes
				
				$result["input_instructions"] = $input_instructions;
				$result["instructions"] = $source_instructions;
				$result["output_instructions"] = $output_instructions;
				$result["environment"] = $renv;
				
				return $result;
				
			} catch (Exception $e) {
				if ($e->getMessage() === "TLE") {
					return ["status" => "TLE"];
				} else {
					return ["status" => "RTE"];
				}
			}
		}
		
		static function sandbox($source) {
			$source_tokens = Tokenizer::to_token_seq($source);
			$source_tree = Program::parse($source_tokens, 0);
			if ($source_tree === NULL) {
				return ["status" => "CE"];
			}
			return grade_one($source_tree, "", "", 64000);
		}
	}

?>