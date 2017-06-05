<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'dom.php';
require_once 'user.php';
require_once 'paginate.php';
require_once 'logger.php';

class EditUsersPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("<h2>Edit users</h2>");
		$this->body_items[] = new Text("
			<p>
			LGN - The user can log in. <br>
			SBT - The user can submit to tasks. <br>
			EOT - The user can edit own tasks. <br>
			PTS - The user can publish new tasks. <br>
			EAT - The user is allowed to edit all tasks. <br>
			APN - The user can access the Admin Panel.
			</p>
");
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
	Logger::notice("Attempted access to edit-permissions.php");
	recover(0);
}

$r = new Renderer(0);
$page = new EditUsersPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page edit-users.php");
	recover(0);
}

?>