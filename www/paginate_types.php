<?php

class PaginateTypes {

	static function get($type) {

		// Put the controller wherever you want within the HTML wrapper
		// but you have to put it somewhere!

		$paginate_limit_controller = "
			<select id='${type}_limit' oninput='${type}_offset_reset()'>
				<option value='10'>10</option>
				<option value='20'>20</option>
				<option value='50'>50</option>
				<option value='100'>100</option>
			</select>
		";

		// The same goes for the result box

		$paginate_result_box = "<div id='${type}_result_box'></div>";

		// And previous/next controller

		$paginate_bidi_controller = "
			<button onclick='${type}_offset_previous()'>Previous</button>
			<button onclick='${type}_offset_next()'>Next</button>
		";

		switch ($type) {
			case 'user_simple':
				return [
					"name" => "user_simple",
					"query" => "select * from users order by id asc",
					"args" => ["limit", "offset"],
					"header" => "<tr><th>User</th></tr>",
					"class_name" => "User",
					"method_name" => "render_row_simple",
					"html" => "
						<div>
							$paginate_limit_controller
							$paginate_bidi_controller
							$paginate_result_box
						</div>
					"
				];
			case 'task_detailed':
				return [
					"name" => "task_detailed",
					"query" => 
"select id, name, statement, author, created_on, status,
 s, ac from tasks t9 inner join (
 select t3.task_id, s, ac from (
  select task_id, count(user_id) ac from (
   (select * from solved) union (select * from attempted)
  ) t1 group by task_id
 ) t3
 inner join
 (
  select task_id, count(user_id) s from solved group by task_id
 ) t4
 on t3.task_id = t4.task_id
) t7 on t9.id = t7.task_id
order by t7.task_id",
					"args" => ["limit", "offset"],
					"header" => "<tr><th>Task name</th><th>Success rate</th></tr>",
					"class_name" => "Task",
					"method_name" => "render_row_detailed",
					"html" => "
						<div>
							$paginate_limit_controller
							$paginate_bidi_controller
							$paginate_result_box
						</div>
					"
				];
			case 'user_task_subs':
				return [
					"name" => "user_task_subs",
					"query" => "select * from submissions where
						user_id = ? and task_id = ? order by id asc",
					"args" => ["user_id", "task_id", "limit", "offset"],
					"header" => "<tr><th>Submission time</th><th>Status</th></tr>",
					"class_name" => "Submission",
					"method_name" => "render_row_simple",
					"html" => "
						<div>
							$paginate_limit_controller
							$paginate_bidi_controller
							$paginate_result_box
						</div>
					"
				];
			case 'all_subs':
				return [
					"name" => "all_subs",
					"query" => "select * from submissions order by id desc",
					"args" => ["limit", "offset"],
					"header" => "<tr><th>User</th><th>Task</th><th>Submission time</th>
						<th>Status</th></tr>",
					"class_name" => "Submission",
					"method_name" => "render_row_all",
					"html" => "
						<div>
							$paginate_limit_controller
							$paginate_bidi_controller
							$paginate_result_box
						</div>
					"
				];
			case 'user_tasks':
				return [
					"name" => "user_tasks",
					"query" => "select * from tasks where author = ? order by id asc",
					"args" => ["author", "limit", "offset"],
					"header" => "<tr><th>Task name:</th></tr>",
					"class_name" => "Task",
					"method_name" => "render_row_simple",
					"html" => "
						<div>
							$paginate_limit_controller
							$paginate_bidi_controller
							$paginate_result_box
						</div>
					"
				];
			default:
				return NULL;
		}
	}
}