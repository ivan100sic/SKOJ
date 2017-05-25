drop database skoj;
create database skoj default character set utf8;
use skoj;

create table users (
	id int unsigned not null auto_increment,
	username varchar(32) not null,
	password varchar(128) not null,
	email varchar(64) not null,
	created_on datetime not null,
	-- status is deprecated, see permissions

	primary key (id),
	unique index id_uq (id asc),
	unique index username_uq (username asc),
	unique index email_uq (email asc)
) engine = InnoDB;


create table tasks (
	id int unsigned not null auto_increment,
	name varchar(32) not null,
	statement blob(8192) not null,
	author int unsigned not null,
	created_on datetime not null,
	status int not null,

	primary key (id),
	unique index id_uq (id asc),
	unique index name_uq (name asc),
	index fk_tasks_users_idx (author asc),
	constraint fk_tasks_users
		foreign key (author)
		references users(id)
		on delete cascade
		on update cascade
) engine = InnoDB;


create table submissions (
	id int unsigned not null auto_increment,
	user_id int unsigned not null,
	task_id int unsigned not null,
	source blob(16384) not null,
	created_on datetime not null,
	status int not null,

	primary key (id),
	unique index id_uq (id asc),
	index fk_submissions_users_idx (user_id asc),
	index fk_submissions_tasks_idx (task_id asc),
	constraint fk_submissions_users
		foreign key (user_id)
		references users (id)
		on delete cascade
		on update cascade,
	constraint fk_submissions_tasks
		foreign key (task_id)
		references tasks(id)
		on delete cascade
		on update cascade
) engine = InnoDB;


create table testcases (
	id int unsigned not null auto_increment,
	name varchar(32) not null,
	task_id int unsigned not null,
	source_input blob(16384) not null,
	source_output blob(16384) not null,
	instruction_limit int unsigned not null,

	primary key (id),
	unique index id_uq (id asc),
	index fk_testcases_tasks_idx (task_id asc),
	constraint fk_testcases_tasks
		foreign key (task_id)
		references tasks(id)
		on delete cascade
		on update cascade
) engine = InnoDB;


create table test_runs (
	submission_id int unsigned not null,
	testcase_id int unsigned not null,
	status int not null,

	primary key (submission_id, testcase_id),
	index fk_test_runs_submissions_idx (submission_id asc),
	index fk_test_runs_testcases_idx (testcase_id asc),
	constraint fk_test_run_submissions
		foreign key (submission_id)
		references submissions(id)
		on delete cascade
		on update cascade,
	constraint fk_test_runs_testcases
		foreign key (testcase_id)
		references testcases(id)
		on delete cascade
		on update cascade
) engine = InnoDB;


create table permissions (
	id int unsigned not null,
	name varchar(64) not null,

	primary key (id),
	unique index id_uq (id asc)
) engine = InnoDB;


create table users_permissions (
	user_id int unsigned not null,
	permission_id int unsigned not null,

	primary key(user_id, permission_id),
	index fk_user_idx (user_id asc),
	index fk_permission_idx (permission_id asc),
	constraint fk_ups_users
		foreign key (user_id)
		references users(id)
		on delete cascade
		on update cascade,
	constraint fk_ups_permissions
		foreign key (permission_id)
		references permissions(id)
		on delete cascade
		on update cascade
) engine = InnoDB;

-- The predefined set of permissions and properties

insert into permissions(id, name) values
	(1, 'LOGIN'),
	(2, 'SUBMIT'),
	(3, 'EDIT_OWN_TASKS'),
	(4, 'PUBLISH_TASKS'),
	(5, 'EDIT_ALL_TASKS'),
	(6, 'ADMIN_PANEL')
;