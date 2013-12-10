<?php 
	include 'page_elements/header.php'; 
	include 'php_scripts/db_connect.php';
?>

<body>
<div id="banner">
	<?php include 'page_elements/login.php'; ?>
</div>
<div id="below_banner">
<?php include 'page_elements/nav_bar.php'; ?>
	
<div id='content'>	
<?php
	foreach($_SESSION['cart'] as $val){
		$query = "SELECT sp_name, price_sale, id_spatula FROM full_spatula WHERE id_spatula=".$val['id_spatula'].";";
		$result = $link->query($query);
		$cart_item[] = $result->fetch_assoc();
	}
	
	echo "<ul id='shopping_cart'>";
	$total = 0;
	foreach($cart_item as $val){
		echo 
			"<li class='item'>
				<div>".$val['sp_name']."</div>
				<div> 
					<label for='quantity_".$val['id_spatula']."'>Quantity:</label>
					<input type='number' id='quantity_".$val['id_spatula']."' value=1 min=1 class='quantity'>
				</div>
				<div class='price_sale'>".$val['price_sale']."</div>
			</li>";
		$total += $val['price_sale'];
	}
	echo "<li>Total: <span id='total'>".$total."</span></li>";
	echo "</ul>";
?>
</div>
</div>
</body>

<?php include 'page_elements/footer.php'; ?>
    
<script type="text/javascript">
	$(document).ready(function(){
		$('.quantity').change(function( event ) {
//			alert('quant changed');
			var total = 0;
			$('#shopping_cart').children('.item').each(function(){
				var price = $(this).find('.price_sale').html(),
				quant = $(this).find('.quantity').val();
//				alert("price: " + price + ", quant: " + quant);
				total += price * quant;
			});
//			alert("new total: " + total);
			$('#total').html(total);
		});
	});
</script> 