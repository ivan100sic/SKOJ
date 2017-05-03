<?php
	require_once 'dom.php';
	class Index extends Page{
		function __construct(){
			parent::__construct();
			$this->toolbar->remove('index');
			$this->toolbar->style("font-style: italic;"); //test for style function from Div
			if($_SESSION['status']==1){
				$this->body_items[]=new EscapedText($_SESSION['username']);
			}
			$this->body_items[]=new Login($_SESSION['status']);
			for($i=1; $i<100; $i++){
				$this->body_items[]=new Text("sample<br>");
			}
		}
	}
	$page=new Index();
	$page->render();
?>