<?php
	interface Renderable {
		function render($env);
	}

	class Text implements Renderable {
		private $data;
		
		function __construct($data) {
			$this->data = $data;
		}
		
		function render($env) {
			echo $this->data;
		}

		/* Getters, setters... */
	}

	class Page implements Renderable {
		protected $head_items;
		protected $body_items;
		
		// trenutno ignorisemo env
		function __construct() {
			$this->head_items = [
				"charset" => new Text("<meta charset='UTF-8'/>"),
				"title" => new Text("<title>SKOJ</title>")
			];
			$this->body_items = [
				new Text("Hello world!")
			];
		}
		
		function render($env) {
			echo "<!DOCTYPE HTML><html><head>";
			foreach ($this->head_items as $key => $value) {
				$value->render($env);
			}
			echo "</head><body>";
			foreach ($this->body_items as $key => $value) {
				$value->render($env);
			}
			echo "</body></html>";
		}		
	};
?>