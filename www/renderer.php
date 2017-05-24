<?php

class Renderer {

	protected $text;
	protected $params;

	function __construct($params) {
		$this->text = [];
		$this->params = $params;
	}

	function print($data) {
		$this->text[] = $data;
	}

	function flush() {
		foreach ($this->text as $data) {
			echo $data;
		}
		$this->text = [];
	}
}

?>