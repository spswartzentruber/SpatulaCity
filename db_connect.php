<?php
$host = 'localhost';
$username = 'root';
$password = 'Three.14159';
$db = 'spatula_city';
$port = '';

$link = mysqli_connect($host, $username, $password, $db);
/*
if(!$link){
	die('could not connect');
} else {
	echo 'connected successfully';
}

mysqli_close($link);
*/
?>