<?php
require_once 'sql.php';
session_start();
$name = $_POST['username'];
$pass = $_POST['password'];
//define if functionality when username or password is empty
$temp1=SQL::get("SELECT level FROM users WHERE username= ?", [$name]);
$temp2=SQL::get("SELECT level FROM users WHERE password= ?", [$pass]);

$tryname=$temp1[0]['level'];
$trypass=$temp2[0]['level'];
if($tryname != 0 && $trypass != 0 && $tryname==$trypass){
	$_SESSION['username']=$name;
	$_SESSION['level']=$tryname;
	$_SESSION['status']=1;
	header('Location: index.php');
	exit();
}else {
	header('Location: index.php');
	exit();
}

?>