<?php 
include 'page_elements/header.php'; 
?>

<body>
<div id="banner">
	<?php include 'login.php'; ?>
</div>
<div id="below_banner">
<?php
include 'page_elements/nav_bar.php';
?>
<div id='content'>
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
</div>
</div>
</body>

<?php include 'page_elements/footer.php'; ?>

<script>
$(document).ready(function(){
	$('#new_spatula_form').submit(function( event ) {
		 // Stop form from submitting normally
		event.preventDefault();
		
		// Send the data using post
		var $form = $( this ),
		url = $form.attr( "action" );
		
		var posting = $.post( url ,  $( "#new_spatula_form" ).serialize() );
		
		posting.done(function(data){
			var message = $( data ).find( ".finish_alert" );
			alert(data);
		});
	});
});
</script>