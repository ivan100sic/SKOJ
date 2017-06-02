<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'dom.php';
require_once 'user.php';
require_once 'paginate.php';

class EditPermissionsPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Edit permissions</h2>");
		$this->body_items[] = new Text("
		<script>
			function toggle_perm(user_id, perm_id) {
				\$.post('toggle-perm.php', {
					'user_id': user_id,
					'perm_id': perm_id
				}, function (data, status) {
					if (status == 'success') {
						var eid = '#edit_perms_link_' + user_id + '_' + perm_id;
						\$(eid).html(data);
					}
				});
			}
		</script>");
		$this->body_items[] = new PaginateFrontend(PaginateTypes::get('edit_perms'));
	}
}

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	recover(0);
}

$r = new Renderer(0);
$page = new EditPermissionsPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>