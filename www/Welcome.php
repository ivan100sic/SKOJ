<?php
require_once 'dom.php';
class welcome extends Page{
	function __construct(){
		/*$this->head_items =[
			"charset" => new Text("<meta charset='UTF-8'/>"),
			"title" => new Text("<title> Welcome </title>")
		];
		$this->body_items =[
			new Text("ay lmao test")
			//add personal greeting by user id
		];*/
		parent::__construct();
		$this->head_items['title']= new Text("<title> Welcome</title>");
	}
}
$page = new welcome();
$page->render();
?>