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

	// $s = "a=0; b=1; i=1; n=24; <in[ b=+ab; a=-ba; i=+i1; ] r=b;";
	// $s = "a = + a 1;";
	
	
	$s =
		"OVAJ PROGRAM PUNI NIZ A PROSTIM BROJEVIMA
		KOJI SU MANJI OD HILJADU

		n = 1000;
		i = 2;
		k = 0;
		<= i n [
			!b.i {
				a.k = i;
				k = +k1;
				j = *ii;
				<= j n [
					b.j = 1;
					j = +ji;
				]
			}
			i = +i1;
		]

		K SADRZI BROJ PROSTIH BROJEVA DOK A SADRZI TE PROSTE
		BROJEVE INDEKSIRANE OD NULA";
	
	
	// $s = "a.4 = 5;";
	
	$token_sequence = Tokenizer::to_token_seq($s);	
	$syntax_tree = Program::parse($token_sequence, 0);	
	$env = new Environment(100000);
	$syntax_tree->run($env);
	
	var_dump($env);
	var_dump($syntax_tree);
?>