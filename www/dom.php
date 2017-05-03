<?php

	class Text {
		private $data;
		
		function __construct($data) {
			$this->data = $data;
		}
		
		function render() {
			echo $this->data, PHP_EOL;
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
			echo EscapedText::convert($this->data),PHP_EOL;
		}

		/* Getters, setters... */
	}
	class Div{ //class used to create and maintain boxed elements on page. 
		//Example: individual news stories should each be a Div object
		protected $class; //m'lady
		protected $s;
		protected $elements;
		function __construct($class,$element){ //agreed on passing array as argument
			$this->class=$class;
			$this->elements=$element;
		}
		function add($key,$element){
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
		function style(){ //for additional control, meant for position and alignment
			$ret="style='";
			foreach(func_get_args() as $arg){
				$ret=$ret." ".$arg;
			}
			$ret=$ret."'";
			$this->s=$ret;
		}
		function render(){ //todo: expand and adapt to determine individual div position, alignment, etc.
			echo"<div class='".$this->class."' ".$this->s.">",PHP_EOL;
			foreach ($this->elements as $key => $value){
				$value->render();
				//echo"<br>"; //idk if this should be here, or somewhere else
			}
			echo"</div>",PHP_EOL;
		}
	}
	
	//Login form
	//Think about implementing tables as class to help sorting and ordering html elements
	class Login extends Div{
		function __construct(){
			$this->class="login";
			$this->elements=[
					'title'=>new Text("<strong>Login:</strong><br>"),
					'name'=>new Text("Username "),
					'inputname'=>new Text("<input type='text' name='username'><br>"),
					'pass'=>new Text("Password "),
					'inputpass'=>new Text("<input type='password' name='password'><br>"),
					'submit'=> new Text("<input type='submit'><br>")
			];
		}
		function render(){
			echo "<form action='action.php' method='post'>",PHP_EOL;
			parent::render();
			echo "</form>",PHP_EOL;
		}
	}

	class Page {
		protected $head_items;
		protected $body_items;
		protected $toolbar;
		
		function __construct() {
			$this->head_items = [
				"charset" => new Text("<meta charset='UTF-8'/>"),
				"title" => new Text("<title>SKOJ</title>"),
				"style" => new Text(" <link rel='stylesheet' href='style.css'>")
			];
			$this->body_items = [
				//new Text("Hello world!")
			];
			$temp=[
					'index' => new Text("<a href='index.php'>Home</a><br/>"),
					'user' => new Text("<a href='user.php'>User</a><br/>"),
					'task' => new Text("<a href='task.php'>Task</a><br/>"),
					//add more
			];
			//Toolbar style
			$this->toolbar= new Div("toolbar",$temp);
			$this->toolbar->add("logout", new Text("<a href='logout.php'>Log Out</a>"));
		}
		
		function render() {
			echo "<!DOCTYPE HTML><html><head>";
			foreach ($this->head_items as $key => $value) {
				$value->render();
			}
			echo "</head><body>",PHP_EOL;
			//new toolbar render
			$this->toolbar->render();
			foreach ($this->body_items as $key => $value) {
				$value->render();
			}
			echo "</body></html>";
		}	
	}
?>