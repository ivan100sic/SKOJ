use skoj;

delete from users;
delete from tasks;
delete from testcases;
delete from users_permissions;
-- see skoj_hash in hash.php
-- 'password'
-- 'sifra'
-- 'troll', this account has no privileges
insert into users(id, username, password, email, created_on) values
	(1, 'ivan100sic', 'abc60fb12cd58e0421069461161e421e81176d82', 'ivan100sic@gmail.com', now()),
	(2, 'dzale', 'fed6f87f04ee830cbc19687369defa9bbdd41a32', 'aleksandar1177@gmail.com', now()),
	(3, 'troll', '080882ec7ab0f02142898c670fae5ce51d26abd2', 'troll@troll.com', now())
;

insert into tasks(id, name, statement, author, created_on, status) values
	(1, 'Фибоначи',
		'
		\\P
			Написати програм који израчунава \\In\\i-ти \\IФибоначијев\\i број и
			смешта га у променљиву \\Ir\\i.
		\\p

		\\P
			Ограничења: \\I0 ≤ n ≤ 46\\i.
		\\p

		\\U
			Променљива \\In\\i садржи редни број \\IФибоначијевог\\i броја
			који треба израчунати.
		\\u

		\\R
			У променљиву \\Br\\b уписати \\If\\Dn\\d\\i, резултат.
		\\r

		\\E
			Пример:\\N
			за \\M\\In\\i=7, \\Ir\\i=13\\m. 
		\\e
		', 1, now(), 1),

	(2, 'swap',
		'
		\\P
			Swap the elements stored in \\Ia\\i and \\Ib\\i.
		\\p
		', 1, now(), 1);

insert into testcases(id, name, task_id, source_input, source_output, instruction_limit) values
	(1, 'mali', 1, 'n=4;', '==3r{@}', 8000),
	(2, 'veliki', 1, 'n=46;', '==1836311903r{@}', 8000),

	(3, 'same', 2, 'a=4;b=4;', '&&==a4==b4{@}', 1000),
	(4, 'diff', 2, 'a=`1;b=7;', '&&==b`1==a7{@}', 1000)
;

insert into users_permissions(user_id, permission_id) values
	(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
	(2, 1), (2, 2), (2, 3)
;
