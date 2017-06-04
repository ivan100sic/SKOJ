<?php

require_once 'dom.php';
require_once 'global.php';
require_once 'user.php';
require_once 'logger.php';

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

			function okd(e) {
				if (e.keyCode == 13 || e.keyCode == 10) {
					register();
				}
			}
		</script>
		<div>
			<p>Register:</p>
			<div>
				Username:
				<p>
					<input type='text' id='username' onkeydown='okd(event)'/>
				</p>

				Email:
				<p>
					<input type='text' id='email' onkeydown='okd(event)'/>
				</p>

				Password:
				<p>
					<input type='password' id='password1' onkeydown='okd(event)'/>
				</p>

				Retype password:
				<p>
					<input type='password' id='password2' onkeydown='okd(event)'/>
				</p>

				<p>
					<button onclick='register()'>Register</button>
				</p>
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

$user = User::construct_safe(get_session_id());
if ($user !== NULL) {
	Logger::notice("Attempted access to register.php by user already logged in");
	recover(0);
}

$r = new Renderer(0);
$page = new RegisterPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page register.php");
	recover(0);
}

?>