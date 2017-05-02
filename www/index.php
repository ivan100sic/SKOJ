<?php
	require_once 'dom.php';
	class Index extends Page{
		function __construct(){
			parent::__construct();
			$this->toolbar->remove('index');
		}
	}
	$page=new Index();
	$page->render();
?>