<?php

/*
	
*/

class PaginateTypes {

	static function get($type) {
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
							Users per page: <br/>
							<input type='text' id='user_simple_limit' name='limit'/> <br/>
							Offset: <br/>
							<input type='text' id='user_simple_offset' name='offset'/> <br/>
							<div id='user_simple_result_box'></div>
							<button onclick='user_simple_post()'>Submit</button>
						</div>
					"
				];
			default:
				return NULL;
		}
	}
}