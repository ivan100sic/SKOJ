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
	
	function __construct($row) {
		$this->id = $row["id"];
		$this->name = $row["name"];
		$this->statement = $row["statement"];
		$this->author = $row["author"];
		$this->created_on = $row["created_on"];
		$this->status = $row["status"];
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
	
	function render_statement($r) {
		$r->print(Markup::convert_to_html($this->statement));
	}

	function render_row_simple($r) {
		$r->print("
			<tr><td>
				<a href='show-task.php?task_id=$this->id'>$this->name</a>
			</td></tr>");
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
			limit 10
			", [$this->id]);

		// var_dump($db);
		$r->print("<div><p>Best solutions</p><table>");
		foreach ($db as $row) {
			$r->print("<tr>
				<td>${row['sid']}</td>
				<td>${row['uid']}</td>
				<td>${row['username']}</td>
				<td>${row['status']}</td>
			</tr>");
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
}
	
?>