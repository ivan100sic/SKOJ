<?php

require_once 'syntax-parse.php';
require_once 'tokenizer.php';
require_once 'environment.php';
require_once 'global.php';
require_once 'renderer.php';

$code = __post__('code');
if ($code === NULL) {
	Logger::notice('Missing code in POST on page sandbox-backend.php');
	exit();
}

$tokens = Tokenizer::to_token_seq($code);
$tree = Program::compile($tokens);

if ($tree === NULL) {
	echo "<p>Compilation error!</p>";
	exit();
}

$r = new Renderer(0);

$env = new Environment(262144);
$err = 0;
try {
	$tree->run($env);
} catch (Throwable $e) {
	$r->print("<p>Program terminated with status: " . $e->getMessage() . "</p>");
	$err = 1;
}
if ($err === 0) {
	$r->print("<p>Program terminated successfully</p>");
}

$env->render($r);
$r->flush();

?>