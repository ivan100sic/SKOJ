<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class SandboxPage extends Page {
	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
			<h2>Sandbox</h2>
			<p>Run any SKOJ code here!</p>
			<textarea id='sandbox_code' rows='25' cols='70'
					onkeydown='tab_hook(event, this)'></textarea>
			<p>
				<button onclick='sandbox_submit()'>Submit</button>
			</p>
			<div class='vspace' id='sandbox_result'></div>
			<script>
				function sandbox_submit() {
					\$('#sandbox_result').html('Running...');
					\$.post('sandbox-backend.php', {
						'code': \$('#sandbox_code').val()
					}, function (data, status) {
						if (status == 'success') {
							\$('#sandbox_result').html(data);
							make_clean();
						}
					});
				}
			</script>
		");
	}
}

$r = new Renderer(0);
$page = new SandboxPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page sandbox.php");
	recover(0);
}

?>