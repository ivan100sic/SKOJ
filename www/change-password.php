<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'user.php';

class ChangePasswordBox {

	function render($r) {
		$r->print("
		<script>
			function change_password_submit() {
				\$.post('change-password-backend.php', {
					'old_password': \$('#old_password').val(),
					'password_1': \$('#password_1').val(),
					'password_2': \$('#password_2').val()
				}, function (data, status) {
					if (status == 'success') {
						\$('#result_box').html(data);
					} else {
						\$('#result_box').html('Task failed successfully');
					}
				});
			}
		</script>
		<div>
			<p>Confirm your old password:</p>
			<input type='password' id='old_password'/>
			<p>Type your new password:</p>
			<input type='password' id='password_1'/>
			<p>Confirm your new password:</p>
			<input type='password' id='password_2'/> <br/>
			<button onclick='change_password_submit()'>Change password</button>
			<p id='result_box'></p>
		</div>");
	}
}

class ChangePasswordPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new ChangePasswordBox();
	}
}

if (get_session_id() === 0) recover(0);

$r = new Renderer(0);
$page = new ChangePasswordPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}


?>