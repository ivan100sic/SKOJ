<?php

class LoginBox {

	function __construct() {}

	function render($r) {
		$r->print("
		<script>
			function login() {
				\$.post('login.php', {
					'username': \$('#username').val(),
					'password': \$('#password').val()
				}, function (data, status) {
					if (status == 'success') {
						\$('#login_result_box').html(data);
					}
					if (data == 'OK') {
						window.location = 'index.php';
					}
				});
			}

			function okd(e) {
				if (e.keyCode == 13 || e.keyCode == 10) {
					login();
				}
			}
		</script>
		<div>
			<p>Login:</p>
			<div>
				<p>Username:</p>
				<input type='text' id='username' onkeydown='okd(event)'/>
				<p>Password:</p>
				<input type='password' id='password' onkeydown='okd(event)'/>
				<p><button onclick='login()'>Log in</button></p>
				<p id='login_result_box'></p>
			</div>
		</div>
		");
	}
}

?>