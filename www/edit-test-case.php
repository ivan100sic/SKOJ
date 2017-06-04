<?php

require_once 'testcase.php';
require_once 'global.php';
require_once 'dom.php';
require_once 'task.php';

class EditTestcaseBox {
	protected $testcase_id;

	function __construct($testcase_id) {
		$this->testcase_id = $testcase_id;
	}

	function render($r) {
		$id = $this->testcase_id;
		$r->print("
			<div>
				<p>Name:</p>
				<input type='text' id='testcase_name' onkeydown='make_dirty()'/>
				<p>Input source code:</p>
				<textarea id='testcase_source_input' rows='20' cols='60'
					onkeydown='tab_hook(event, this)'></textarea>
				<p>Output source code:</p>
				<textarea id='testcase_source_output' rows='20' cols='60'
					onkeydown='tab_hook(event, this)'></textarea>
				<p>Instruction limit (no more than 65536):</p>
				<input type='text' id='testcase_instruction_limit'/>
				<button onclick='testcase_save()'>Save</button>
				<p id='testcase_result_box'></p>
			</div>");
		$r->print("
			<script>
				\$.post('get_testcase_attrs.php',
					{
						type: 'name',
						id: '$id'
					},
					function (data, status) {
						if (status == 'success') {
							\$('#testcase_name').val(data);
						}
					});

				\$.post('get_testcase_attrs.php',
					{
						type: 'source_input',
						id: '$id'
					},
					function (data, status) {
						if (status == 'success') {
							\$('#testcase_source_input').val(data);
						}
					});

				\$.post('get_testcase_attrs.php',
					{
						type: 'source_output',
						id: '$id'
					},
					function (data, status) {
						if (status == 'success') {
							\$('#testcase_source_output').val(data);
						}
					});

				\$.post('get_testcase_attrs.php',
					{
						type: 'instruction_limit',
						id: '$id'
					},
					function (data, status) {
						if (status == 'success') {
							\$('#testcase_instruction_limit').val(data);
						}
					});

				function testcase_save() {
					var name = \$('#testcase_name').val();
					var source_input = \$('#testcase_source_input').val();
					var source_output = \$('#testcase_source_output').val();
					var instruction_limit = \$('#testcase_instruction_limit').val();
					\$('#testcase_result_box').html('...');
					\$.post('save-test-case.php', {
						'id': $id,
						'name': name,
						'source_input': source_input,
						'source_output': source_output,
						'instruction_limit': instruction_limit
					}, function (data, status) {
						if (status == 'success') {
							\$('#testcase_result_box').html(data);
							if (data.includes('Changes saved!')) {
								make_clean();
							}
						}
					});
				}
				
			</script>
		");
	}
}

class EditTestcasePage extends Page {
	function __construct($testcase_id) {
		parent::__construct();
		$this->body_items[] = new EditTestcaseBox($testcase_id);
	}
}

$testcase_id = __get__('id');
$testcase = Testcase::construct_safe($testcase_id);
if ($testcase === NULL) {
	Logger::notice('Missing or bad id in GET on page edit-test-case.php');
	recover(0);
}
$task = $testcase->get_task_id();

if (!Task::authorize_edit($task, get_session_id())) {
	Logger::notice('User not authorized to edit task on edit-task.php');
	recover(0);
}

$r = new Renderer(0);
$page = new EditTestcasePage($testcase_id);
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error('Exception occurred on page edit-test-case.php');
	recover(0);
}

?>