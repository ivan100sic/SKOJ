<?php

class PaginateFrontend {

	protected $html;
	protected $name;
	protected $args;

	function __construct($params) {
		$this->name = $params['name'];
		$this->html = $params['html'];
		$this->args = $params['args'];
	}

	function render($r) {
		$name = $this->name;
		$html = $this->html;
		$js_post_list = "type: '$name'";
		foreach ($this->args as $arg) {
			$js_post_list .= ", $arg: \$('#${name}_${arg}').val()";
		}
		$js = "
			<script>
				function ${name}_callback(data, status) {
					if (status == 'success') {
						\$('#${name}_result_box').html(data);
					}
				}
				function ${name}_post() {
					$.post(
						'paginate_backend.php',
						{ $js_post_list },
						${name}_callback
					);
				}
			</script>";
		$r->print($html);
		$r->print($js);
	}
}