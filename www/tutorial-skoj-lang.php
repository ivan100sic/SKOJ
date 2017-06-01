<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'loginbox.php';
require_once 'user.php';

class TutorialSkojLangPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
		<h2> Introduction </h2>
		<p>
			SKOJ is a programming language designed to be easy to learn and
			simple to compile. The language was created as part of the SKOJ system,
			a website where you can create, edit and solve programming challenges.
			The reason for the existence of this language is that
			not every hosting provider gives shell access, and access to installed compilers
			(if any). The current SKOJ platform only requires a PHP server and a database
			connection.
		</p>

		<h2>Formal description</h2>

		<p>
			For those of you who love formal language theory,
			<a href='skoj-lang-formal.php'>here</a> is the formal description of the
			SKOJ language. The most important feature of the language is the use of prefix
			syntax, which is easier to parse.
		</p>

		<h2>Type system and variables</h2>

		<p>
			The only type in SKOJ is the 32-bit signed integer. Integer overflow gives undefined 
			results. All logical operators consider 0 as false and any other value as true. There
			are 26 hybrid variables, denoted by lowercase letters a-z. Each hybrid variable holds 
			a scalar integer value, when referenced on its own. In addition to this, each of these
			hybrid variables can also be subscripted using the dot operator, acting as an
			array. These two aspects of the same variable behave independently, i.e. writing to
			a variable as an array will not overwrite its value as a scalar. There is no way
			to directly copy the contents of one entire array into another.
		</p>

		<h2>Runtime</h2>

		<p>
			Division by zero results in a runtime error. All variables are zero-initialized.
			Memory consumption (number of different vector elements generated through use of
			the dot operator) is not monitored. The number of primitive instructions executed
			is monitored and reported to the user.
		</p>

		<h2>Input/output and testing</h2>

		<p>
			When you submit a solution to a problem, each time your solution is run on one of
			the test cases, three things happen: First, the input program is executed. It
			initializes some of the variables, according to the problem statement. Next, your
			solution is executed. Your solution should, based on values of input variables, 
			write correct values to output variables. Finally, the output program is executed,
			verifying your solution. <b> The output program should execute the cookie statement,
			signalling that your solution for that test case is correct. </b> Executing the
			cookie statement in input programs/solutions is allowed, but has no effect (other
			than bumping the instruction count).
		</p>

		<h2>Comments and details about parsing</h2>

		<p>
			All characters not defined in the language are ignored. This includes: whitespace,
			uppercase letters, parentheses - '(' and ')', hash - '#', etc. You can safely 'glue'
			together variable names, integer literals and operators, as long as no ambiguity
			occurs. The parser is greedy and it first attempts to parse the input sequence as a 
			two-character operator. This means that, for example, 'x = &lt;&lt;= a b c;' will
			result in a compilation error, as it will be interpreted as x = &lt;&lt; = a b c.
			However, 'x = &lt; &lt;= a b c;' is a valid assignment statement.
		</p>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialSkojLangPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>