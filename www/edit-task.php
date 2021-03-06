<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'user.php';
require_once 'task.php';
require_once 'testcase.php';
require_once 'logger.php';

class TestCaseEditor {
	protected $task_id;

	function __construct($task_id) {
		$this->task_id = $task_id;
	}

	function render($r) {
		$db = SQL::get("select * from testcases
			where task_id = ?", [$this->task_id]);

		$r->print("
			<p><a href='new-test-case.php?task_id=$this->task_id'>Add test case</a></p>
			<table>
		");
		foreach($db as $row) {
			$testcase = new Testcase($row);
			$testcase->render_edit_row($r);
		}
		$r->print("</table>");
	}
}

class EditTaskForm {

	protected $task_id;

	function __construct($task_id) {
		$this->task_id = $task_id;
	}

	function render($r) {
		$r->print("
			<div>
				<p>Problem title:</p>
				<input type='text' id='task_name' onkeydown='make_dirty()'/>
				<p>Problem statement:</p>
				<textarea id='task_statement' rows='15' cols='70'
					onkeydown='tab_hook(event, this)'></textarea>
				<p>
					<button onclick='task_parse()'>Check & Preview</button>
				</p>
				<div id='task_parse_result_box'></div>
				<p>
					<button onclick='task_save()'>Save changes</button>
				</p>
				<p id='task_result_box'></p>
				<p>Test cases:</p>
			");

		(new TestCaseEditor($this->task_id))->render($r);
		$r->print("
			</div>
			<script>
				\$.post('get_task_attrs.php',
					{
						type: 'name',
						id: '$this->task_id'				
					},
					function (data, status) {
						if (status == 'success') {
							\$('#task_name').val(data);
						}
					});
				\$.post('get_task_attrs.php',
					{
						type: 'statement',
						id: '$this->task_id'
					},
					function (data, status) {
						if (status == 'success') {
							\$('#task_statement').val(data);
						}
					});

				function task_save() {
					var name = \$('#task_name').val();
					var statement = \$('#task_statement').val();
					\$('#task_result_box').html('...');
					\$.post('save-task.php', {
						'id': $this->task_id,
						'name' : name,
						'statement': statement
					}, function(data, status) {
						if (status == 'success') {
							\$('#task_result_box').html(data);
							if (data == 'Changes saved!') {
								make_clean();
							}
						}
					});
				}

				function task_parse() {
					var statement = \$('#task_statement').val();
					\$.post('parse-task.php', {
						'statement': statement
					}, function(data, status) {
						if (status == 'success') {
							\$('#task_parse_result_box').html(data);
						}
					});
				}

				function delete_test_case(id) {
					\$.post('delete-test-case.php', {
						'id': id					
					},
					function(data, status) {
						if (status == 'success' && data == 'ok') {
							\$('#testcase_edit_row_'+id).remove();
						}
					});
				}
			</script>
		");
	}
}

class EditTaskPage extends Page {

	function __construct($task_id) {
		parent::__construct();
		$this->body_items[] = new EditTaskForm($task_id);
	}
}

$task_id = __get__('task_id');
$session_id = get_session_id();
$task = Task::construct_safe($task_id);
$user = User::construct_safe($session_id);

if ($task === NULL) {
	Logger::notice('Missing task_id in GET on page edit-task.php');
	recover(0);
}

if ($user === NULL) {
	Logger::notice('Attempted access to page edit-task.php');
	recover(0);
}

if ($task->get_author() == get_session_id()) {
	if (!$user->has_permission("EDIT_OWN_TASKS")
		&& !$user->has_permission("EDIT_ALL_TASKS"))
	{
		Logger::notice('User not authorized to edit task on edit-task.php');
		recover(0);
	}
} else {
	if (!$user->has_permission("EDIT_ALL_TASKS")) {
		Logger::notice('User not authorized to edit task on edit-task.php');
		recover(0);
	}
}

// Good to go

$r = new Renderer(0);
$page = new EditTaskPage($task_id);
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error('Exception occurred on page edit-task.php');
	recover(0);
}

?>
