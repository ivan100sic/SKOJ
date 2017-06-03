<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'sql.php';

class ManageUngradedPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Manage ungraded submissions</h2>");
		$c = SQL::get("select count(*) c from submissions where status = -1", [])[0]['c'];
		$this->body_items[] = new Text("<p># of ungraded submissions: $c</p>
			<p><button onclick='regrade_all()'>Regrade</button></p>
			<div class='vspace' id='regrade_replies'>
			</div>
			<script>
				function regrade_all() {
					\$.get('grade-one.php', function (data, status) {
						if (status == 'success') {
							\$('#regrade_replies').append(data);
							if (data[4] == 'N') {
								cond = false;
							} else {
								regrade_all();
							}
						}
					});
				}
			</script>");
	}
}

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	recover(0);
}

$r = new Renderer(0);
$page = new ManageUngradedPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}


?>
