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
			<input type='hidden' id='${name}_offset' value='0'/>

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
				function ${name}_offset_previous() {
					var limit = parseInt(\$('#${name}_limit').val());
					var offset = parseInt(\$('#${name}_offset').val());
					offset -= limit;
					if (offset < 0) {
						offset = 0;
					}
					\$('#${name}_offset').val(offset);
					${name}_post();
				}
				function ${name}_offset_next() {
					var limit = parseInt(\$('#${name}_limit').val());
					var offset = parseInt(\$('#${name}_offset').val());
					offset += limit;
					\$('#${name}_offset').val(offset);
					${name}_post();
				}
				function ${name}_offset_reset() {
					\$('#${name}_offset').val(0);
					${name}_post();
				}

				${name}_offset_reset();
				${name}_post();
			</script>";
		$r->print($html);
		$r->print($js);
	}
}

?>