<?php

require_once 'dom.php';

class Markup {

	// vraca NULL ako nije validan markup
	static function convert_to_html($raw) {
		$html = "";
		
		$raw = EscapedText::convert($raw);
		
		$n = strlen($raw);
		$i = 0;
		
		$markup_stack = [];
		$ms_size = 0;

		$markup_state = '';
		
		while ($i < $n) {
			if ($raw[$i] == '\\') {
				if ($i == $n - 1) {
					return NULL;
				}
				$i++;					
				switch ($raw[$i]) {
					case '\\':
						$html .= '\\';
						break;
						
					case 'B':
						$html .= "<b>";
						$markup_stack[$ms_size++] = "B";
						break;
					case 'b':
						$html .= "</b>";
						if ($markup_stack[--$ms_size] !== 'B') {
							return NULL;
						}
						break;
						
					case "I":
						$html .= "<i>";
						$markup_stack[$ms_size++] = "I";
						break;
					case "i":
						$html .= "</i>";
						if ($markup_stack[--$ms_size] !== 'I') {
							return NULL;
						}
						break;
						
					case "G":
						$html .= "<sup>";
						$markup_stack[$ms_size++] = "G";
						break;
					case "g":
						$html .= "</sup>";
						if ($markup_stack[--$ms_size] !== 'G') {
							return NULL;
						}
						break;
						
					case "D":
						$html .= "<sub>";
						$markup_stack[$ms_size++] = "D";
						break;
					case "d":
						$html .= "</sub>";
						if ($markup_stack[--$ms_size] !== 'D') {
							return NULL;
						}
						break;
						
					case "P":
						if ($markup_state === 'p' || $markup_state === 'sp') {
							return NULL;
						}
						$markup_state .= 'p';
						$html .= "<p>";
						$markup_stack[$ms_size++] = "P";
						break;
					case "p":
						$html .= "</p>";
						if ($markup_stack[--$ms_size] !== 'P') {
							return NULL;
						}
						$markup_state = substr($markup_state, 0, -1);
						break;
						
					case "U":
						if ($markup_state !== '') {
							return NULL;
						}
						$markup_state = 's';
						$html .= "<div class='statement_input'>";
						$markup_stack[$ms_size++] = "U";
						break;
					case "u":
						$html .= "</div>";
						if ($markup_stack[--$ms_size] !== 'U') {
							return NULL;
						}
						$markup_state = '';
						break;
						
					case "R":
						if ($markup_state !== '') {
							return NULL;
						}
						$markup_state = 's';
						$html .= "<div class='statement_output'>";
						$markup_stack[$ms_size++] = "R";
						break;
					case "r":
						$html .= "</div>";
						if ($markup_stack[--$ms_size] !== 'R') {
							return NULL;
						}
						$markup_state = '';
						break;
						
					case "E":
						if ($markup_state !== '') {
							return NULL;
						}
						$markup_state = 's';
						$html .= "<div class='statement_example'>";
						$markup_stack[$ms_size++] = "E";
						break;
					case "e":
						$html .= "</div>";
						if ($markup_stack[--$ms_size] !== 'E') {
							return NULL;
						}
						$markup_state = '';
						break;
						
					case 'N':
						$html .= "<br/>";
						break;
						
					default:
						return NULL;
				}
				$i++;
			} else {
				$html .= $raw[$i];
				$i++;
			}
		}
		
		if ($ms_size == 0) {
			return $html;
		} else {
			return NULL;
		}
	}

	private $escaped_html;
	
	function __construct($raw) {
		$this->escaped_html = Markup::convert_to_html($raw);
	}
	
	function render() {
		$this->escaped_html->render();
	}
	
}

?>