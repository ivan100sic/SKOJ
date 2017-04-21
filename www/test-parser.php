<?php

	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

	require_once('tokenizer.php');
	require_once('syntax-parse.php');

	/*
	
	a = 0;
	b = 1;
	i = 1;
	<in [
	  b = +ab;
	  a = -ba;
	]
	r = b;
	
	*/
	
	

	$s = "a=0; b=1; i=1; <in[ b=+ab; a=-ba; ] r=b;";
	// $s = "a = + a 1;";
	
	var_dump($s);
	
	$token_sequence = Tokenizer::to_token_seq($s);
	
	var_dump($token_sequence);
	
	$syntax_tree = Program::parse($token_sequence, 0);
	
	var_dump($syntax_tree);
?>