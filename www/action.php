<?php
require_once 'sql.php';
require_once 'user.php';
session_start();
$name = $_POST['username'];
$pass = $_POST['password'];
$check = User::check($name, $pass);
if($check==-2){
	echo'name';
}elseif ($check==-1){
	echo 'pass';
}elseif ($check>=1){
	echo 'true';
	$_SESSION['user']=$name;
	$_SESSION['level']=SQL::get("SELECT level FROM users WHERE id = ?",[$check]);
}

?>