<?php

require_once 'dom.php';
require_once 'global.php';

class RegisterBox {

	function __construct() {}

	function render($r) {
		$r->print("
		<script>
			function callback(data, status) {
				if (status == 'success') {
					\$('#register_result_box').html(data);
				} else {
					\$('#register_result_box').html('Network error');
				}
			}

			function register() {
				var username_ = \$('#username').val();
				var email_ = \$('#email').val();
				var password1_ = \$('#password1').val();
				var password2_ = \$('#password2').val();

				\$.post('register_backend.php', {
					username: username_,
					email: email_,
					password1: password1_,
					password2: password2_
				}, callback);

				\$('#register_result_box').html('...');
			}			
		</script>
		<div>
			<p>Register:</p>
			<div>
				Username:<br/>
				<input type='text' id='username'/><br/>

				Email:<br/>
				<input type='text' id='email'/><br/>

				Password:<br/>
				<input type='password' id='password1'/> <br/>

				Retype password:<br/>
				<input type='password' id='password2'/> <br/>

				<button onclick='register()'>Register</button>
			</div>
			<p id='register_result_box'></p>
		</div>
		");
	}
}

class RegisterPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new RegisterBox();
	}
}

$r = new Renderer(0);
$page = new RegisterPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	recover(0);
}

?>