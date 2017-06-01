<?php

require_once 'sql.php';
require_once 'markup.php';
require_once 'user.php';

class Task {
	
	private $id;	
	private $name;	
	private $statement;	
	private $author;	
	private $created_on;	
	private $status;

	private $solved_count;
	private $att_sol_count;
	
	function __construct($row) {
		$this->id = $row["id"];
		$this->name = $row["name"];
		$this->statement = $row["statement"];
		$this->author = $row["author"];
		$this->created_on = $row["created_on"];
		$this->status = $row["status"];

		if (isset($row['s'])) {
			$this->solved_count = (int)$row['s'];
		} else {
			$this->solved_count = NULL;
		}

		if (isset($row['ac'])) {
			$this->att_sol_count = (int)$row['ac'];
		} else {
			$this->att_sol_count = NULL;
		}
	}
	
	static function construct_safe($id) {
		
		$db = SQL::get("select * from tasks where id = ?", [$id]);
		
		if (count($db) !== 1) return NULL;
		return new Task($db[0]);
	}

	function get_author() {
		return $this->author;
	}

	function get_name() {
		return $this->name;
	}

	function get_statement() {
		return $this->statement;
	}

	function get_id() {
		return $this->id;
	}
	
	function render_statement($r) {
		$r->print(Markup::convert_to_html($this->statement));
	}

	function render_link($r) {
		$r->print("<a href='show-task.php?task_id=$this->id'>");
		(new EscapedText($this->name))->render($r);
		$r->print("</a>");
	}

	function render_row_simple($r) {
		$r->print("<tr><td>");
		$this->render_link($r);
		$r->print("</td><td>");
	}

	function render_row_detailed($r) {
		$r->print("<tr><td>");
		$this->render_link($r);
		$r->print("</td><td class='centered'>");
		$r->print("$this->solved_count/$this->att_sol_count");
		$r->print("</td></tr>");
	}

	function render_best_solutions($r) {
		// Worst query I've written in a while
		$db = SQL::get("
			select t5.id sid, t6.id uid, t6.username, t5.status from
			(
			select t4.id, t4.user_id, t4.status from (
					select min(t2.id) i, t2.user_id
					from
						(select user_id u, min(status) m
							from submissions
							where task_id = ?
							and status >= 0
							group by user_id
							order by m asc
						) t1 inner join
							submissions t2
						on m = status and u = user_id
					group by
						t2.user_id
				) t3 inner join submissions t4 on t3.i = t4.id
			) t5
			inner join users t6 on t5.user_id = t6.id
			order by status asc
			limit 10
			", [$this->id]);

		// var_dump($db);
		$r->print("<div><h3>Best solutions</h3><table>");
		foreach ($db as $row) {
			$sid = $row['sid'];
			$uid = $row['uid'];
			$status = $row['status'];

			$r->print("<tr><td>");
			User::construct_safe($uid)->render_link($r);
			$r->print("</td><td><a href='show-submission.php?id=$sid'>
				$status</a></td></tr>");
		}
		$r->print("</table></div>");
	}

	static function authorize_edit($task_id, $user_id) {
		$task = Task::construct_safe($task_id);
		$user = User::construct_safe($user_id);

		if ($task === NULL) return 0;
		if ($user === NULL) return 0;

		if ($task->get_author() == $user_id) {
			return $user->has_permission("EDIT_OWN_TASKS")
				|| $user->has_permission("EDIT_ALL_TASKS");
		} else {
			return $user->has_permission("EDIT_ALL_TASKS");
		}
	}

	static function create_new($author) {
		$db = SQL::run("insert into tasks(name, statement, author, created_on, status)
			values (concat('Task ', now()), '', ?, now(), 1)", [$author]);
		return SQL::last_insert_id();
	}
}
	
?>