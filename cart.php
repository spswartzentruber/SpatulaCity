<?php 
	include 'page_elements/header.php'; 
	include_once 'db_connect.php';
?>

<body>
<div id="banner">
	<?php include 'login.php'; ?>
</div>
<div id="below_banner">
<?php include 'page_elements/nav_bar.php'; ?>
	
    <div id='content'>	
    <?php
		include_once 'db_connect.php';
        //Fetch data for each spatula in the cart
        foreach($_SESSION['cart'] as $val){
            $query = "SELECT sp_name, price_sale, id_spatula FROM full_spatula WHERE id_spatula=".$val['id_spatula'].";";
            $result = $link->query($query);
            $cart_item[] = $result->fetch_assoc();
        }
        
        //Build our shopping cart display.
        echo "
            <table id='shopping_cart'>
                <tr>
                    <th>Spatula</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            ";
        $total = 0;
        foreach($cart_item as $val){
            echo 
                "<tr class='item'>
                    <td>".$val['sp_name']."</td>
                    <td><input type='number' id='quantity_".$val['id_spatula']."' value=1 min=1 class='quantity'></td>
                    <td><div class='price_sale'>".$val['price_sale']."</div></td>
                    <td><button>Remove</button></td>
                </tr>";
            $total += $val['price_sale'];
        }
        echo "
                <tr>
                    <td></td>
                    <td>Total:</td>
                    <td><span id='total'>".$total."</span></td>
                    <td></td>
                </tr>
            </table>
            <br />
            ";
    ?>
    </div>
    <select id="delivery_dropdown"></select><select id="payment_dropdown"></select>
    <fieldset id='shipping_address_fields'>
        <table>
          <tr>
            <td>Street:</td>
            <td><input type="text" required name="address[street]"></td>
          </tr>
          <tr>
            <td>City:</td>
            <td><input type="text" required name="address[city]"></td>
          </tr>
          <tr>
            <td>ZIP:</td>
            <td><input type="text" required name="address[zip]"></td>
          </tr>
          <tr>
            <td>State:</td>
            <td><select id="state_dropdown"></select></td>
          </tr>
        </table>
    </fieldset>
</div>
</body>

<?php include 'page_elements/footer.php'; ?>
    
<script type="text/javascript">
	$(document).ready(function(){
		//Start using jQuery to populate dropdown menus.
		var delivery_dropdown_data = $.post( 'form_handler.php' , { delivery_dropdown : 1 } )
		, payment_dropdown_data = $.post( 'form_handler.php' , { payment_dropdown : 1 } )
		, state_dropdown_data = $.post( 'form_handler.php' , { state_dropdown : 1 } );
		
		delivery_dropdown_data.done( function( data ){
//			var foo = $(data).find('.content').text();
			console.log('got us some delivery options');
			console.log(data);
			$.each( JSON.parse(data), function( i, val ){
//				console.log('adding option '.val);
				$('#delivery_dropdown').append($('<option></option>').val(val.id_delivery_service).html(val.name));
			});
		});
		
		payment_dropdown_data.done( function( data ){
//			var foo = $(data).find('.content').text();
			console.log('got us some payment options');
			console.log(data);
			$.each( JSON.parse(data), function( i, val ){
//				console.log('adding option ' + val);
				$('#payment_dropdown').append($('<option></option>').val(val.id_payment_method).html(val.name));
			});
		});
		
		state_dropdown_data.done( function( data ){
//			var foo = $(data).find('.content').text();
			console.log('got us some states');
			console.log(data);
			$.each( JSON.parse(data), function( i, val ){
//				console.log('adding option ' + val);
				$('#state_dropdown').append($('<option></option>').val(val.id_state).html(val.name));
			});
		});
		//Stop using jQuery to populate dropdown menus
		
		$('.quantity').change(function( event ) {
//			alert('quant changed');
			var total = 0;
			$('.item').each(function(){
				var price = $(this).find('.price_sale').html(),
				quant = $(this).find('.quantity').val();
//				alert("price: " + price + ", quant: " + quant);
				total += Math.round(price * quant * 100)/100;
			});
//			alert("new total: " + total);
			$('#total').html(total);
		});
	});
</script> 
