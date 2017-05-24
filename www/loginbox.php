<?php

class LoginBox {

	function __construct() {}

	function render($r) {
		$r->print("<div>
			<p>Login:</p>
			<form action='login.php' method='POST'>
				Username:<br/>
				<input type='text' name='username'/> <br/>
				Password:<br/>
				<input type='password' name='password'/> <br/>
				<input type='submit' value='Log in'/>
			</form>
		</div>");
	}
}

?>