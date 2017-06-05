<?php 
require_once 'global.php';
require_once 'sql.php';
require_once 'dom.php';
require_once 'user.php';
require_once 'logger.php';

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	Logger::notice('Attempted unauthorized access to edit-user.php');
	recover(0);
}

class EditUserPage extends Page{
	function __construct($user_id){
		parent::__construct();
		$this->body_items[] = new Text("
		<script>
		function callback(data, status) {
			if (status == 'success') {
				\$('#result_box').html(data);
			} else {
				\$('#result_box').html('Network error');
			}
		}
		function save() {
			var username_ = \$('#username').val();
			var email_ = \$('#email').val();
			var password_ = \$('#password').val();
			\$.post('edit-user-backend.php', {
				'id': '$user_id',
				'username': username_,
				'email': email_,
				'password': password_
			}, callback);

			\$('#register_result_box').html('...');
		}
		function generate() {
			var n = 8, s = '', i;
			for (i=0; i<n; i++) {
				s += '' + Math.floor(Math.random() * 10);
			}
			\$('#password').val(s);
		}
		function okd(e) {
			if (e.keyCode == 13 || e.keyCode == 10) {
				save();
			}
		}
		</script>
			<div>
			<h2>Edit user:</h2>
				<div>
					<p>
						Username: 
					</p>
					<p>
						<input type='text' id='username' onkeydown='okd(event)'/>
					</p>
					<p>
						Email:
					</p>
					<p>
						<input type='text' id='email' onkeydown='okd(event)'/>
					</p>
					<p>
						Password:
					</p>
					<p>
						<input type='text' id='password' onkeydown='okd(event)'/>
						<button onclick='generate()'>Generate Password</button>
					</p>
					<p>
						<button onclick='save()'>Save user details</button>
					</p>

					<p id='result_box'></p>
				</div>
			</div>
		<script>
				\$.post('get_user_attrs.php',
					{
						type: 'username',
						id: '$user_id'				
					},
					function (data, status) {
						if (status == 'success') {
							\$('#username').val(data);
						}
					});
				\$.post('get_user_attrs.php',
					{
						type: 'email',
						id: '$user_id'				
					},
					function (data, status) {
						if (status == 'success') {
							\$('#email').val(data);
						}
					});
		</script>
				");
	}
}

$user_id = __get__('user_id');
$user = User::construct_safe($user_id);
if ($user === NULL) {
	Logger::notice('Missing or bad user_id in GET on page edit-user.php');
	recover(0);
}

$r = new Renderer(0);
$page = new EditUserPage($user->get_id());
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page edit-user.php");
	recover(0);
}

?>