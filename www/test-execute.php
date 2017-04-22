<?php

	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

	require_once('tokenizer.php');
	require_once('syntax-parse.php');
	require_once('environment.php');

	/*
	
	a = 0;
	b = 1;
	i = 1;
	n = 24;
	<in [
	  b = +ab;
	  a = -ba;
	  i = +i1;
	]
	r = b;
	
	*/	

	$s = "a=0; b=1; i=1; n=24; <in[ b=+ab; a=-ba; i=+i1; ] r=b;";
	// $s = "a = + a 1;";
	
	$token_sequence = Tokenizer::to_token_seq($s);	
	$syntax_tree = Program::parse($token_sequence, 0);	
	$env = new Environment(10000);
	$syntax_tree->run($env);
	
	var_dump($env);
?>