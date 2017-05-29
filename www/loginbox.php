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
		</script>
		<div>
			<p>Login:</p>
			<div>
				<p>Username:</p>
				<input type='text' id='username'/>
				<p>Password:</p>
				<input type='password' id='password'/>
				<p><button onclick='login()'>Log in</button></p>
				<p id='login_result_box'></p>
			</div>
		</div>
		");
	}
}

?>