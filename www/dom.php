<?php

require_once 'renderer.php';

class Text {
	private $data;
	
	function __construct($data) {
		$this->data = $data;
	}
	
	function render($r) {
		$r->print($this->data);
	}

	/* Getters, setters... */
}

class EscapedText {
	
	private $data;
	
	function __construct($data) {
		$this->data = $data;
	}
	
	static function convert($data) {
		return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5);
	}
	
	function render($r) {
		$r->print(EscapedText::convert($this->data));
	}

	/* Getters, setters... */
}

class Page {
	protected $head_items;
	protected $body_items;
	
	function __construct() {
		$this->head_items = [
			"charset" => new Text("<meta charset='UTF-8'/>"),
			"title" => new Text("<title>SKOJ</title>")
		];
		$this->body_items = [];
	}
	
	function render($r) {
		$r->print("<!DOCTYPE HTML><html><head>");
		foreach ($this->head_items as $key => $value) {
			$value->render($r);
		}
		$r->print("</head><body>");
		foreach ($this->body_items as $key => $value) {
			$value->render($r);
		}
		$r->print("</body></html>");
	}	
}

?>