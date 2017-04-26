<?php

	require_once('sql.php');

	function version_1() {
		
		/* Users, Ivan and Aleksandar */
		
		/* Prepare by deleting everything */
		
		SQL::run("delete from test_runs;", []);
		SQL::run("delete from testcases;", []);
		SQL::run("delete from submissions;", []);
		SQL::run("delete from tasks;", []);
		SQL::run("delete from users;", []);
		
		$e = SQL::run("insert into users(id, username, password, email, created_on, status) values
			(1, 'ivan100sic', 'password', 'ivan100sic@gmail.com', now(), 1),
			(2, 'dzale', 'sifra', 'aleksandar1177@gmail.com', now(), 1);", []);
		
		/* The one and only task */
		
		$task_statement = "
		\\P
			Написати програм који израчунава n-ти \\IФибоначијев\\i број и
			смешта га у <п>променљиву \Br\b.
		\\p
		
		\\P
			Ogranicenja: \\I0 ≤ n ≤ 46\\i.
		\\p
		
		\\U
			Promenljiva \Bn\b sadrzi redni broj \\Ifibonacijevog\\i broja
			koji treba izracunati.
		\\u
		
		\\R
			U promenljivu \Br\b upisati \\If\\Dn\\d\\i, rezultat.
		\\r
		";
		
		SQL::run("insert into tasks(id, name, statement, author, created_on, status) values
			(1, 'fibonacci', ?, 1, now(), 1)", [$task_statement]);
			
		/* Two testcases for this task */
		$in1 = "n=4;";
		$in2 = "n=46;";
		$out1 = "r==3{@}";
		$out2 = "r==1836311903{@}";
		SQL::run("insert into testcases(id, name, task_id, source_input, source_output, instruction_limit) values
			(1, 'mali', 1, ?, ?, 8000),
			(2, 'veliki', 1, ?, ?, 8000)", [$in1, $out1, $in2, $out2]);
		
		/* A submission by Ivan */
		
		$source = "a = 0;
		b = 1;
		i = 1;
		<in [
		  b = +ab;
		  a = -ba;
		]
		r = b;";
		
		SQL::run("insert into submissions(id, user_id, source, created_on, status) values
			(1, 1, ?, now(), -1)", [$source]);
	
	}
	
	version_1();
	
?>
