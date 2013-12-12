<?php 
	include 'page_elements/header.php'; 
?>

<body>
<div id="banner">
	<?php include 'login.php'; ?>
</div>
<div id="below_banner">
<?php
	session_start();
	include 'db_connect.php';
	include 'page_elements/nav_bar.php';
	
	$id_spatula = $_GET['id_spatula'];
	
	$query = 
		"
		SELECT sp_name as 'name', sp_manufacturer as manufacturer, sp_color as color, image_url, info as description, id_spatula
		FROM spatula
		WHERE id_spatula=$id_spatula;
		";
	$result = $link->query($query);
	$spatula_array = $result->fetch_assoc();
	
	$query =
		"
		SELECT name
		FROM spatula_has_occassion
			JOIN occassion on occassion.id_occassion = spatula_has_occassion.fk_ck_id_occassion
		WHERE fk_ck_id_spatula = $id_spatula
		ORDER BY name;
		";
	$result = $link->query($query);
	while($row = $result->fetch_assoc()){
		$occasions[] = $row['name'];
	}
//	print_r($occasions);
?>

<div id='content'>
	<div id='spatula_line_one'>
        <div class="nailthumb-container" id='spatula_image'><img src="img/<?php echo $spatula_array['image_url'] ?>.jpg" / id="spatula_thumbnail"></div>
        <h1 id='spatula_name'><?php echo $spatula_array['name'] ?></h1>
        <div id='add_to_cart'>
            <form action='form_handler.php' method='POST' class= 'cart_button'>
                <input type='hidden' name='add_to_cart[id_spatula]' value='<?php echo $spatula_array['id_spatula']; ?>'>
                <input type='hidden' name='add_to_cart[sp_name]' value='<?php echo $spatula_array['name']; ?>'>
                <input type='submit' value='Add to Cart'>
            </form>
        </div>
    </div>
    <p id='spatula_description'><?php echo $spatula_array['description'] ?></p><br/>
    <div id='occasions_list'>
        <h2>This spatula is great for...</h2>
        <ul>
            <?php
            foreach($occasions as $foo){
                echo "<li>$foo</li>";
            }
            ?>
        </ul>
    </div>
</div>
</body>

<?php include 'page_elements/footer.php'; ?> 

 <script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.nailthumb-container').nailthumb({width:300,height:300,method:'resize',fitDirection:'top left'});
	
		$('.cart_button').submit(function( event ) {
		 // Stop form from submitting normally
		event.preventDefault();
		
		// Get some values from elements on the page:
		var $form = $( this ),
		url = $form.attr( "action" );
		
		// Send the data using post
		var posting = $.post( url , $form.serialize());
		
		//Grey out button to disallow same item being added to cart
		$(this).addClass('disabled');

		var cart_update = $.post('page_elements/nav_bar.php');
		cart_update.done(function(data){
			$('#navMenu').empty().append(data);
		});
	});
});
</script>