<?php

require_once 'global.php';
require_once 'dom.php';
require_once 'logger.php';

class TutorialMarkupPage extends Page {

	function __construct() {
		parent::__construct();
		$this->body_items[] = new Text("
			<h2><a href='tutorials.php'>Tutorials</a> :: Markup language description</h2>
			<h3>List of section tags</h3>

			<p>
				The following tags represent sections of your problem. You cannot put the
				P tag inside another P tag, or any other tag inside another section tag,
				but you can put the P tag into any other section tag.
			</p>
			<table>
				<tr>
					<td>
						\\P ... \\p
					</td>
					<td>
						Paragraph
					</td>
				</tr>
				<tr>
					<td>
						\\U ... \\u
					</td>
					<td>
						Task input specification
					</td>
				</tr>
				<tr>
					<td>
						\\R ... \\r
					</td>
					<td>
						Task output specification
					</td>
				</tr>
				<tr>
					<td>
						\\E ... \\e
					</td>
					<td>
						Example input/output
					</td>
				</tr>
			</table>

			<h3>List of text formatting tags</h3>
			<p>
				Use the following tags to style your problem statement.
				You can nest these tags as you wish. You can even wrap entire
				sections with these tags. 
			</p>
			<table class='narrow'>
				<tr>
					<td>
						\\B ... \\b
					</td>
					<td>
						Bold
					</td>
				</tr>
				<tr>
					<td>
						\\I ... \\i
					</td>
					<td>
						Italic
					</td>
				</tr>
				<tr>
					<td>
						\\G ... \\g
					</td>
					<td>
						Superscript
					</td>
				</tr>
				<tr>
					<td>
						\\D ... \\d
					</td>
					<td>
						Subscript
					</td>
				</tr>
			</table>

			<p>
				In addition, you can use the \\N tag to enter line breaks. To display a backslash,
				use \\\\. Whitespace is not preserved. All other non-whitespace printable
				characters are preserved. You can use UTF-8 characters.
			</p>
		");
	}
}

$r = new Renderer(0);
$page = new TutorialMarkupPage();
try {
	$page->render($r);
	$r->flush();
} catch (Exception $e) {
	Logger::error("Exception occurred on page tutorial-markup.php");
	recover(0);
}

?>