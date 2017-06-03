<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class TutorialExamplesPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
		<h2> Example 1: Sort </h2>
		<h3> Input program 1: </h3>
<pre>
n = 3;
a.0 = 1;
a.1 = 5;
a.2 = 3;
</pre>

		<h3> Output program 1: </h3>
<pre>
k = 1;
!= a.0 1 { k = 0; }
!= a.1 3 { k = 0; }
!= a.2 5 { k = 0; }
k { @ }
</pre>

		<h3> One possible solution, based on bubble sort </h3>
<pre>
i = 0;
< i n [
	j = + i 1;
	< j n [
		> a.i a.j {
			t = a.i;
			a.i = a.j;
			a.j = t;
		}
		j = + j 1;
	]
	i = + i 1;
]
</pre>

		<p> You can use all the features of the SKOJ
		language in input/output programs. </p>

		<h3> Input program 2: </h3>

<pre>
n = 50;
i = 0;
< i n [
	a.i = - 50 i;
	i = + i 1;
]
</pre>

		<p> This will generate 50 integers, with values 50, 49, ..., 1.

		<h3> Output program 2: </h3>
<pre>
n = 50;
i = 0;
k = 1;
< i n [
	!= a.i + i 1 {
		k = 0;
	}
	i = + i 1;
]
k { @ }
</pre>


</pre>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialExamplesPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page tutorial-examples.php");
	recover(0);
}

?>