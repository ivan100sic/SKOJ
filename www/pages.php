<?php
include 'dom.php';
//NOT USED
//General welcome page for all users, filler for index.php;
//should contain more options in toolbar in regards to session
//toolbar and header should be generated from data which would be passed as constructor parameters
class MainFamily extends Page{
	protected $toolbar; //
	protected $header;
	function __construct(){
		//parent::__construct(); //for testing
		
		$this->toolbar = [ //to be placed in <div> element
			"go_index"=> new Text("<a href='index.php'>dear fucking god</a>"),
			"go_test"=>new Text("<a href='index2.php'>dear fucking god</a>")
			//ideas for link
		];
		$this->head_items = [
				"charset" => new Text("<meta charset='UTF-8'/>"),
				"title" => new Text("<title> Welcome </title>")
		];
		$this->body_items = [
				new Text("Default text;")
		];
	}
	function render() { 
		echo "<!DOCTYPE HTML><html><head>";
		foreach ($this->head_items as $key => $value) {
			$value->render();
		}
		echo "</head><body>";
		foreach ($this->body_items as $key => $value) {
			$value->render();
		}
		echo "</body></html>";
	}	
}
?>