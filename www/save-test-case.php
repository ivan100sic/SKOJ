<?php

require_once 'global.php';
require_once 'testcase.php';
require_once 'task.php';
require_once 'sql.php';
require_once 'tokenizer.php';
require_once 'syntax-parse.php';

$id = __post__('id');
$name = __post__('name');
$source_input = __post__('source_input');
$source_output = __post__('source_output');
$instruction_limit = __post__('instruction_limit');

function bad_post() {
	echo 'Error: Bad POST request';
	exit();
}

// authorize
if ($id === NULL) bad_post();
$testcase = Testcase::construct_safe($id);
if ($testcase === NULL) bad_post();
$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	bad_post();
}

// Verify integrity
// Try to compile source_input
$input_tokens = Tokenizer::to_token_seq($source_input);
$input_syntax_tree = Program::compile($input_tokens);
if ($input_syntax_tree === NULL) {
	echo "Input source code does not compile!";
	exit();
}

//
$output_tokens = Tokenizer::to_token_seq($source_output);
$output_syntax_tree = Program::compile($output_tokens);
if ($output_syntax_tree === NULL) {
	echo "Output source code does not compile!";
	exit();
}

$has_cookie = False;
for ($i = 0; $i < $output_tokens->size(); $i++) {
	if ($output_tokens->get($i) == 'cookie') {
		$has_cookie = True;
	}
}

if (!$has_cookie) {
	echo "The output source code does not contain the cookie statement!";
	exit();
}

$instruction_limit = (int)$instruction_limit;
if ($instruction_limit < 1) {
	echo "Instruction limit is not a positive integer, please check!";
	exit(0);
}

if ($instruction_limit > 65536) {
	echo "Instruction limit set to 65536. ";
}

$db = SQL::run("update testcases set
	name = ?,
	source_input = ?,
	source_output = ?,
	instruction_limit = ?
	where id = ?",

	[$name, $source_input, $source_output, $instruction_limit, $id]
);

if (!$db) {
	echo "Database error!";
} else {
	echo "Changes saved!";
}

?>