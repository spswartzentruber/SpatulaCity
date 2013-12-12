<?php
include_once '../db_connect.php';

//Login
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
//		echo "Login successful.  Welcome $username";
		$_SESSION['username'] = $username;
	} 
	else 
	{
//		echo "Incorrect username or password.";
	}
}

//Logout
if( $_POST['logout'] == 1 )
{
//	echo $_SESSION['username']." logged out.";
	unset($_SESSION['username']);
}

//Print appropriate HTML based on if user is logged in or not
if(empty($_SESSION['username']))
{
	echo "
	<div id='login_div'>
		<form action='page_elements/login.php' method='post' id='login'>
			<fieldset>
				<legend>Login:</legend>
				username: <input type='text' required name='login[username]'> password: <input type='password' required name='login[password]'><input type='submit' value='login' class='loginout_button'>
			</fieldset>
		</form>
	</div>
	";
} else {
	echo
	"
	<div id='login_div'>
		<p>Welcome, ".$_SESSION['username']."!</p>
		<form action='page_elements/login.php' method='post' id='logout'>
			<input type='submit' value='logout' class='loginout_button'>
		</form>
	</div>
	";
}
?>