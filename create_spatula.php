<?php 
session_start();
include 'header.php'; 
?>

<body>

<?php
include 'nav_bar.php';
?>

<form action="php_scripts/form_handler.php" method="post" id="new_spatula_form">
	<fieldset>
        <legend>New Spatula:</legend>
        Name: <input type="text" required name="spatula[name]"><br>
        Manufacturer: <input type="text" required name="spatula[manufacturer]"><br>
        Color: <input type="text" required name="spatula[color]"><br>
        Num in stock: <input type="text" required name="spatula[inventory]"><br>
        Image URL: <input type="text" required name="spatula[image]"><br>
        Sale Price: <input type="number" required name="spatula[sale_price]"><br>
        Retail Price: <input type="number" required name="spatula[retail_price]"><br>
        Description: <input type="textarea" required name="spatula[description]"><br>
        <input type='submit' value='submit'>
	</fieldset>
</form>
</body>

<?php include 'footer.php'; ?>

<script>
$(document).ready(function(){
	$('#new_spatula_form').submit(function( event ) {
		 // Stop form from submitting normally
		event.preventDefault();
		
		// Send the data using post
		var $form = $( this ),
		url = $form.attr( "action" );
		
		var posting = $.post( url ,  $( "#new_spatula_form" ).serialize() );

	});
});
</script>