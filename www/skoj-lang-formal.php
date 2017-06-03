<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class SkojLangFormalPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
<pre>
skoj_program:
	  statement_list
;

statement_list:
	  statement statement_list
	| NONE
;

statement:
	  if_statement
	| while_statement
	| assignment_statement
	| cookie_statement
;

if_statement:
	  rval LEFT_BRACE statement_list RIGHT_BRACE
;

while_statement:
	  rval LEFT_BRACKET statement_list RIGHT_BRACKET
;

assignment_statement:
	  lval ASSIGNMENT_OPERATOR rval SEMICOLON
;

cookie_statement:
	  COOKIE
;

lval:
	  variable
	| variable DOT rval
;

variable:
	  LOWERCASE_LETTER
;

rval:
	  lval
	| literal
	| unary_expression
	| binary_expression
;

literal:
	  DIGIT_SEQUENCE
;

unary_expression:
	  UNARY_OPERATOR rval
;

binary_expression:
	  BINARY_OPERATOR rval rval
;
</pre>

		<p>
			Here, NONE denotes the empty token, COOKIE is &apos;@&apos;, ASSIGNMENT_OPERATOR is
			'='. Unary operators are given in the following table:
		</p>
		<table>
			<tr> <td>`</td> <td>Unary minus</td> </tr>
			<tr> <td>!</td> <td>Logical not</td> </tr>
			<tr> <td>!</td> <td>Bitwise not</td> </tr>
		</table>
		<p>
			Binary operators are given in the following table:
		</p>
		<table>
			<tr> <td> </td> <td>Arithmetic operators</td> </tr>
			<tr> <td>+</td> <td>Addition</td> </tr>
			<tr> <td>-</td> <td>Subtraction</td> </tr>
			<tr> <td>*</td> <td>Multiplication</td> </tr>
			<tr> <td>/</td> <td>Division</td> </tr>
			<tr> <td>%</td> <td>Remainder</td> </tr>

			<tr> <td> </td> <td>Logical and bitwise operators</td> </tr>
			<tr> <td>&</td> <td>Bitwise AND</td> </tr>
			<tr> <td>&&</td> <td>Logical AND</td> </tr>
			<tr> <td>|</td> <td>Bitwise OR</td> </tr>
			<tr> <td>||</td> <td>Logical OR</td> </tr>
			<tr> <td>^</td> <td>Bitwise XOR</td> </tr>
			<tr> <td>&lt;&lt;</td> <td>Bitwise left shift</td> </tr>
			<tr> <td>&gt;&gt;</td> <td>Bitwise right shift</td> </tr>

			<tr> <td> </td> <td>Comparison operators</td> </tr>
			<tr> <td>==</td> <td>Equal</td> </tr>
			<tr> <td>!=</td> <td>Not equal</td> </tr>
			<tr> <td>&lt;</td> <td>Less than</td> </tr>
			<tr> <td>&gt;</td> <td>Greater than</td> </tr>
			<tr> <td>&lt;=</td> <td>Less than or equal to</td> </tr>
			<tr> <td>&gt;=</td> <td>Greater than or equal to</td> </tr>
		</table>
		");
	}
}

$r = new Renderer(0);
$page = new SkojLangFormalPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page skoj-lang-formal.php");
	recover(0);
}

?>