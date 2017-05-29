<?php

class Renderer {

	protected $text;
	protected $params;
	public $temp;

	function __construct($params) {
		$this->text = [];
		$this->params = $params;
		$this->temp = [];
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