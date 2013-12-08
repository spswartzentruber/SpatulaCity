<?php

session_start();
include 'db_connect.php';

if( !empty( $_POST['login'] ) )
{
	$username = $_POST['login']['username'];
	$password = $_POST['login']['password'];
	
	$query = 
		"
		SELECT username 
		FROM user
		WHERE '$username' = username AND '$password' = password;
		";
	
	$result = 	$link->query( $query );
	if( $result->num_rows > 0 )
	{
		echo "Login successful.  Welcome $username";
		$_SESSION['username'] = $username;
	} 
	else 
	{
		echo "Incorrect username or password.";
	}
}

if( $_POST['logout'] == 1 )
{
	echo $_SESSION['username']." logged out.";
	unset($_SESSION['username']);
}

?>