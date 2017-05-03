<?php
	require_once 'dom.php';
	class Index extends Page{
		function __construct(){
			parent::__construct();
			$this->toolbar->remove('index');
			$this->toolbar->style("font-style: italic;"); //test for style function from Div
			$this->body_items[]=new Login();
			for($i=1; $i<100; $i++){
				$this->body_items[]=new Text("sample<br>");
			}
		}
	}
	$page=new Index();
	$page->render();
?>