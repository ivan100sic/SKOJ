<?php

require_once 'user.php';
require_once 'global.php';
require_once 'dom.php';

class HallOfFameBox {
	function render($r) {
		$r->print("<p>Hall of Fame:");
		$r->print("<table>");
		$db = SQL::get("select *, (
				select count(*) from tasks t2
				where
					(select count(*) from submissions where
						user_id = t1.id and
						task_id = t2.id and
						status >= 0
					) > 0
			) c1 from users t1 order by c1 desc, id asc", []);

		foreach ($db as $row) {
			$user = new User($row);
			$r->temp['user_solved_problems'] = $row['c1'];
			$user->render_row_hall_of_fame($r);
		}
		$r->print("</table>");
	}
}

class HallOfFamePage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new HallOfFameBox();
	}
}

$r = new Renderer(0);
$page = new HallOfFamePage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>