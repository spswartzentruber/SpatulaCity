<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.10.3.custom/plugin/jquery.nailthumb.1.1.min.js"></script>

<link href="jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
<link href="jquery-ui-1.10.3.custom/plugin/jquery.nailthumb.1.1.min.css" rel="stylesheet" type="text/css" />
<link href="css/main.css" rel="stylesheet" type="text/css" />

<?php
session_start();
if(empty($_SESSION['username']))
{
	echo "
	<form action='php_scripts/login.php' method='post' id='login'>
		<fieldset>
			<legend>Login:</legend>
			username: <input type='text' required name='login[username]'> password: <input type='password' required name='login[password]'><input type='submit' value='login'>
		</fieldset>
	</form>
	";
} else {
	echo
	"
	<p>Welcome, ".$_SESSION['username']."!</p>
	<button id='logout'>logout</button>
	";
}
?>
</head>

<script>
$(document).ready(function(){
	$('#login').submit(function( event ) {
		 // Stop form from submitting normally
		event.preventDefault();
		
		// Send the data using post
		var $form = $( this ),
		url = $form.attr( "action" );
		
		var posting = $.post( url , $form.serialize() );
		
		posting.done(function( data ) {
			alert(data);
		});
		
//		location.reload();
	});
	
	$('#logout').click(function( event ) {
//		alert('logout clicked');
		$.post( 'login.php' , { logout : 1 } );
//		location.reload();
	});

});
</script>

