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
	class Div{ //class used to create and maintain boxed elements on page. 
		//Example: individual news stories should each be a Div object
		private $id; //added this so different box styles can be implemented
		private $elements;// i meant for div elements to contain Text or EscapedText objects
		function __construct($id,$element){ //dumb to pass whole array, better to give each elems individually
			$this->id=$id;
			$this->elements=$element;
			//overloads on hold for testing
			//rethink this, reason: function with array as arg better than function with infinite args
			/*$this->elements=null;
			foreach (func_get_args() as $arg){ //default constructor. more to be added as the need arises
				$this->elements[]= $arg;
			}*/
		}
		function add($key,$element){
			//todo: implement overloads as in function below, to add the ability to create a default box with only one argument, in which name=null
			$this->elements[$key]=$element;
		}
		function remove(){ //if no args, removes the last element, else it deletes the elements with keys given as arguments
			if(func_num_args()==0){
				array_pop($this->elements);
			} else{
				foreach (func_get_args() as $key){
					unset($this->elements[$key]);
				}
			}
		}
		function render(){ //todo: expand and adapt to determine individual div position, alignment, etc.
			echo"<div id=$this->id>";
			foreach ($this->elements as $key => $value){
				$value->render();
				//echo"<br>"; //idk if this should be here, or somewhere else
			}
			echo"</div>";
		}
	}

	class Page {
		protected $head_items;
		protected $body_items;
		protected $toolbar;
		//protected $style; //for css, is not needed, i can just put STYLE item in HEAD_ITEMS
		
		function __construct() {
			$this->head_items = [
				"charset" => new Text("<meta charset='UTF-8'/>"),
				"title" => new Text("<title>SKOJ</title>"),
				"style" => new Text(" <link rel='stylesheet' href='style.css'>")
			];
			$this->body_items = [
				new Text("Hello world!")
			];
			//toolbar trough the new class
			$temp=[
					'index' => new Text("<a href='index.php'>Home</a><br/>"), //all pages have back to index, except the index page, so it seems more efficient to just put the link in the default Page class
					'user' => new Text("<a href='user.php'>User</a><br/>"),
					'task' => new Text("<a href='task.php'>Task</a><br/>"),
					//add more
			];
			//maybe implement toolbar as child of Div
			$this->toolbar= new Div("toolbar",$temp);
			$this->toolbar->add("logout", new Text("<a href='logout.php'>Log Out</a>"));
			/*$this->toolbar=[
					'index' => new Text("<a href='index.php'>Home</a><br/>"), //all pages have back to index, except the index page, so it seems more efficient to just put the link in the default Page class
					'user' => new Text("<a href='user.php'>User</a><br/>"),
					'task' => new Text("<a href='task.php'>Task</a><br/>"),
					//add more
			];*/
			$this->style= new Text(" <link rel='stylesheet' href='styles.css'>");
		}
		
		function render() {
			echo "<!DOCTYPE HTML><html><head>";
			foreach ($this->head_items as $key => $value) {
				$value->render();
			}
			//new toolbar render
			$this->toolbar->render();
			/*echo "</head><body>";
			echo "<div style='color:#f44242; border-style:dotted;' align='left'>";
			foreach ($this->toolbar as $key =>$value){
				$value->render();
			}
			echo"</div>";
			$this->toolbar[]= new Text("<a href='logout.php'>User</a><br/>");*///adds logout link to end of toolbar each time it is rendered. Consider a different approach
			foreach ($this->body_items as $key => $value) {
				$value->render();
			}
			echo "</body></html>";
		}	
	}
?>