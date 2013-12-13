<?php include 'page_elements/header.php'; ?>

<body>
	<div id="banner">
		<?php include 'login.php'; ?>
	</div>
	<div id="below_banner">
		<?php include 'page_elements/nav_bar.php'; ?>
		<div id='content'>
			<form action="form_handler.php" method="post" id="new_spatula_form">
				<fieldset>
					<legend>New Spatula:</legend>
						<table>
							<tr>
								<td>Name:</td>
								<td><input type="text" required name="spatula[name]"></td>
							</tr>
							<tr>
								<td>Manufacturer:</td>
								<td><input type="text" required name="spatula[manufacturer]"></td>
							</tr>
							<tr>
								<td>Color:</td>
								<td><input type="text" required name="spatula[color]"></td>
							</tr>
							<tr>
								<td>Num in stock:</td>
								<td><input type="text" required name="spatula[inventory]"></td>
							</tr>
							<tr>
								<td>Image URL:</td>
								<td><input type="text" required name="spatula[image]"></td>
							</tr>
							<tr>
								<td>Sale Price:</td>
								<td><input type="number" required name="spatula[sale_price]"></td>
							</tr>
							<tr>
								<td>Retail Price:</td>
								<td><input type="number" required name="spatula[retail_price]"></td>
							</tr>
							<tr>
								<td>Description:</td>
								<td><input type="textarea" required name="spatula[description]"></td>
							</tr>
							<tr>
								<td>Occasions:</td>
								<td id = 'occasions_boxes'></td>
							</tr>
						</table>
					<input type='submit' value='submit'>
					<input type='submit' value='reset'>
				</fieldset>
			</form>
		</div>
	</div>
</body>

<?php include 'page_elements/footer.php'; ?>

<script>
$(document).ready(function(){
	var occasion_list = $.post( 'form_handler.php' , { occasion_list : 1 } );
	
	//Setup Occasions checkboxes
	delivery_dropdown_data.done( function( data ){
//		var foo = $(data).find('.content').text();
		console.log('here are our occasions');
		console.log(data);
		$.each( JSON.parse(data), function( i, val ){
//				console.log('adding option '.val);
			$('#occasions_boxes').append($('<input />', {type: 'checkbox', id: 'occasion'+val.id_occasion, value: val.id_occasion}));
			$('#occasions_boxes').append($('<label />', {for: 'occasion'+val.id_occasion, text: val.name}));
		});
	});
	
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