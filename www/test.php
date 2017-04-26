<?php

	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

	require_once('tokenizer.php');
	require_once('syntax-parse.php');
	require_once('environment.php');
	require_once('dom.php');
	require_once('markup.php');
	require_once('task.php');
	require_once('submission.php');
	
	function test_execute() {

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
		
		/*
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
		*/
		
		$s = "
			a.0 = 0;
			a.1 = 1;
			i = 2;
			n = 40;
			! > i n [
				a.i = + (a.-i1) (a.-i2);
				i = +i1;
			]
			i = `1;
			> i `n [
				a.i = - (a.+i2) (a.+i1);
				i = -i1;
			]
		";
		
		$test_primer = "
			n = 3;
			a.0 = 1;
			a.1 = 16;
			a.2 = 22;
			
		";
		
		$verify = "
			== b 39 {
				@
			}
		";
		
		
		// $s = "a.4 = 5;";
		
		$token_sequence = Tokenizer::to_token_seq($s);	
		$syntax_tree = Program::parse($token_sequence, 0);	
		$env = new Environment(100000);
		$syntax_tree->run($env);
		
		var_dump($env);
		var_dump($syntax_tree);
	}
	
	function test_parser() {
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
	}
	
	function test_markup() {
		
		$s = "
		\\P
			Написати програм који израчунава n-ти \\IФибоначијев\\i број и
			смешта га у <п>променљиву \Br\b.
		\\p
		
		\\P
			Ogranicenja: \\I0 ≤ n ≤ 46\\i.
		
		\\U
			Promenljiva \Bn\b sadrzi redni broj \\Ifibonacijevog\\i broja
			koji treba izracunati.
		\\u
		
		\\R
			U promenljivu \Br\b upisati \\If\\Dn\\d\\i, rezultat.
		\\r
		";

		(new Text(Markup::convert_to_html($s)))->render();
	}
	
	function test_task() {
		$task = Task::construct_safe(1);
		var_dump($task);
		$task->render_statement();
	}
	
	function test_grade() {
		$submission = Submission::construct_safe(1);
		$submission->grade();		
	}
	
	test_grade();
?>