<?php

	class Text {
		private $data;
		
		function __construct($data) {
			$this->data = $data;
		}
		
		function render() {
			echo $this->data;
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
		
		function render() {
			echo EscapedText::convert($this->data);
		}

		/* Getters, setters... */
	}

	class Page {
		protected $head_items;
		protected $body_items;
		protected $toolbar;
		
		function __construct() {
			$this->head_items = [
				"charset" => new Text("<meta charset='UTF-8'/>"),
				"title" => new Text("<title>SKOJ</title>")
			];
			$this->body_items = [
				new Text("Hello world!")
			];
			$this->toolbar=[
					'go_index' => new Text("<a href='index2.php'>Index2</a><br/>"),
					'go_user' => new Text("<a href='user.php'>User</a><br/>")
					//add more
			];
		}
		
		function render() {
			echo "<!DOCTYPE HTML><html><head>";
			foreach ($this->head_items as $key => $value) {
				$value->render();
			}
			echo "</head><body>";
			echo "<div style='color:#f44242; border-style:dotted;' align='left'>";
			foreach ($this->toolbar as $key =>$value){
				$value->render();
			}
			echo"</div>";
			foreach ($this->body_items as $key => $value) {
				$value->render();
			}
			echo "</body></html>";
		}	
	}
?>