<?php

// Accepts a POST request and replies with a html <div> element, which
// contains the paginated table and control links

// Include everything
require_once 'sql.php';
require_once 'global.php';
require_once 'paginate_types.php';

require_once 'user.php';
require_once 'task.php';
require_once 'submission.php';
require_once 'test_run.php';
require_once 'testcase.php';

class PaginateBackend {

	// "select" query to run
	protected $query;
	// int, maximum number of rows returned
	protected $limit;
	// int, starting offset
	protected $offset;
	// class to construct using a row
	protected $class_name;
	// method to call on constructed objects
	// the method shall render one table row
	protected $method_name;
	// table header
	protected $header;
	// Query arguments, drawn from POST
	protected $args;
	// Additional options for the table
	protected $table_options;

	// Constructor. Can swallow anything for limit and offset.
	function __construct($params) {
		$this->query = $params['query'];
		$this->class_name = $params['class_name'];
		$this->method_name = $params['method_name'];
		$this->header = $params['header'];
		$this->table_options = $params['table_options'];

		$limit_int = (int)__post__('limit');
		if ($limit_int < 1) {
			$limit_int = 1;
		}
		if ($limit_int > 100) {
			$limit_int = 100;
		}
		$this->limit = $limit_int;

		$offset_int = (int)__post__('offset');
		if ($offset_int < 0) {
			$offset_int = 0;
		}
		if ($offset_int > 1000000000) {
			$offset_int = 1000000000;
		}
		$this->offset = $offset_int;

		$this->args = [];
		foreach ($params['args'] as $arg) {
			if ($arg !== 'offset' && $arg !== 'limit') {
				$this->args[] = __post__($arg);
			}
		}
	}

	function render($r) {
		$q = "$this->query limit $this->offset, $this->limit";
		$db = SQL::get($q, $this->args);
		$objs = [];
		foreach ($db as $row) {
			$objs[] = new $this->class_name($row);
		}
		// Render table and header
		$r->print("<table $this->table_options>$this->header");
		foreach ($objs as $obj) {
			$obj->{$this->method_name}($r);
		}
		$r->print("</table>");
	}
}

$type = __post__('type');

$r = new Renderer(0);
$pg = NULL;
$pg_type_obj = PaginateTypes::get($type);
if ($pg_type_obj !== NULL) {
	$pg = new PaginateBackend($pg_type_obj);
}

if ($pg !== NULL) {
	try {
		$pg->render($r);
		$r->flush();
	} catch (Exception $e) {
		// This page shall echo an empty string if an exception happens
	}
} else {
	recover(0);
}

?>