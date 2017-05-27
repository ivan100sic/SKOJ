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
					"header" => "",
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
			case 'task_simple':
				return [
					"name" => "task_simple",
					"query" => "select * from tasks order by id asc",
					"args" => ["limit", "offset"],
					"header" => "<tr><td>Task name:</td></tr>",
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