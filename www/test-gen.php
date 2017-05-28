<?php
require_once 'hash.php';
echo"<!DOCTYPE HTML><html><head><meta charset='UTF-8'/></head><body>";
//(1, 'ivan100sic', 'abc60fb12cd58e0421069461161e421e81176d82', 'ivan100sic@gmail.com', now()),
for($i=0;$i<1000;$i++){
	$id=$i+3;
	echo"(".$id.",'user".$id."', '".skoj_hash('user'.$id, 'pass'.$id)."', 'user".$id."@gmail.com', now()),";
	echo"<br>";
}
echo"</body></html>";
?>