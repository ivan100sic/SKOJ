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
			$this->body_items[]=new LoginBox($_SESSION['status']);
			for($i=1; $i<100; $i++){
				$this->body_items[]=new Text("sample \n");
			}
		}
	}
	$page=new Index();
	$page->add_script(new Text("<script>
$(document).ready(function(){
	 $('#login').click(function(){	
		  username=$('#name').val();
		  password=$('#pass').val();
		  $.ajax({
		   type: 'POST',
		   url: 'action.php',
			data: 'name='+username+'&pwd='+password,
		   success: function(html){    
			if(html=='true')    {
			 
			}
			else    {
			$('#add_err').css('display', 'inline', 'important');
			 $('#add_err').html('<img src='images/alert.png' />Wrong username or password');
			}
		   },
		   beforeSend:function()
		   {
			$('#add_err').css('display', 'inline', 'important');
			$('#add_err').html('<img src='images/ajax-loader.gif' /> Loading...')
		   }
		  });
		return false;
	});
});
</script>"));
	$page->render();
?>