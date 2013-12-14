<?php
	function query_to_array($query_str, $db){
		$query_result = $db->query($query_str);
		while($row = $query_result->fetch_assoc()){
			$result[] = $row;
		}
		return $result;
	}
	
	session_start(); 
	include 'db_connect.php';
	date_default_timezone_set('America/Indianapolis');
	
	//Create spatula.
	if(!empty($_POST['spatula']))
	{
		$form = $_POST['spatula'];
		
		$name = $form['name'];
		$manufacturer = $form['manufacturer'];
		$color = $form['color'];
		$inventory = $form['inventory'];
		$image = $form['image'];
		$description = $form['description'];
		$sale_price = $form['sale_price'];
		$retail_price = $form['retail_price'];
		
		$time = date('Y-m-d H:i:s',time());
		
		//INSERT into spatula table
		$query = "INSERT INTO `spatula_city`.`spatula` (`sp_name`, `sp_manufacturer`, `sp_color`, `create_time`, `image_url`, `info`) VALUES ('$name', '$manufacturer', '$color', '$time', '$image', '$description');";
		$result = $link->query($query);
		
		//Store auto_incrimented Spatula ID as I need it for my inserts into my relational tables
		$id_spatula = $link->insert_id;
		
		//INSERT into inventory table
		$query = "INSERT INTO `spatula_city`.`inventory` (`fk_id_spatula`, `price_retail`, `price_sale`, `inv_count`, `last_update`) VALUES ('$id_spatula', '$retail_price', '$sale_price', '$inventory', '$time');";
		$link->query($query);
		
		//INSERT into occassion (yes, I misspelled it...) relational table
		print_r($form['occasion']);
		foreach($form['occasion'] as $foo){
			$query = "INSERT INTO `spatula_city`.`spatula_has_occassion` (`fk_ck_id_spatula`, `fk_ck_id_occassion`) VALUES ('$id_spatula', '$foo');";
			$link->query($query);
			echo $query;
		}
		
		echo "
			<div class='finish_alert'>
				$name added to database
			</div>";
	}
	
	//Add to cart
	if(!empty($_POST['add_to_cart'])){
		$_SESSION['cart'][] =  $_POST['add_to_cart'];
	}
	
	//Generate data for dropdown of delivery services
	if(!empty($_POST['delivery_dropdown'])){
		$query = "
			SELECT *
			FROM delivery_service
		";
		$results = $link->query($query);
		while($row = $results->fetch_assoc()){
			$foo[] = array(
				'id_delivery_service' => $row['id_delivery_service']
				, 'name' => $row['name']
			);
		}
		echo json_encode($foo);
	}
	
	//Generate data for dropdown of payment methods
	if(!empty($_POST['payment_dropdown'])){
		$query = "
			SELECT *
			FROM payment_method
		";
		$results = $link->query($query);
		while($row = $results->fetch_assoc()){
			$foo[] = array(
				'id_payment_method' => $row['id_payment_method']
				, 'name' => $row['name']
			);
		}
		echo json_encode($foo);
	}
	
	//Generate data for dropdown of state list
	if(!empty($_POST['state_dropdown'])){
		$query = "
			SELECT *
			FROM state
		";
		$results = $link->query($query);
		while($row = $results->fetch_assoc()){
			$foo[] = array(
				'id_state' => $row['id_state']
				, 'name' => $row['name']
			);
		}
		echo json_encode($foo);
	}
	
	//Generate occasion checkboxes
	if(!empty($_POST['occasion_list'])){
		$query = 
			"SELECT * 
			FROM `occassion` 
			ORDER BY 'name' DESC;";
		$occasions = query_to_array($query,$link);
		echo json_encode($occasions);
	}
	
	//Purchase a cart full of spatulas
	if(!empty($_POST['new_spatula_form'])){
//		echo "beginning purchase steps";
		
		$form = $_POST['new_spatula_form'];
		
		$street = $form['address']['street'];
		$city = $form['address']['city'];
		$zip = $form['address']['zip'];
		$state = $form['address']['state'];
		$delivery = $form['delivery'];
		$payment = $form['payment'];
		$cart = $_SESSION['cart'];
		$time = date('Y-m-d H:i:s',time());
		
		if( empty( $_SESSION['username'] ) ){
			$username = 'no_username';
		} else {
			$username = $_SESSION['username'];
		}
		
		print_r(array($street, $city, $zip, $state, $delivery, $payment, print_r($cart), $time, $username));
		
		// 1. Create address
		$query = "INSERT INTO `spatula_city`.`address` (`street`, `city`, `zip`, `fk_id_state`) VALUES ('$street', '$city', '$zip', '$state');";
		$result = $link->query($query);
		$id_address = $link->insert_id;
//		echo $query;
		
		// 2. Create purchase entry
		$query = "INSERT INTO `spatula_city`.`purchase` (`fk_id_address`, `fk_user_username`, `purchase_time`, `fk_id_delivery_service`, `fk_id_payment_method`) VALUES ('$id_address', '$username', '$time', '$delivery', '$payment');";
		$result = $link->query($query);
		$id_purchase = $link->insert_id;
//		echo $query;
		
		// 3. Relate purchase entry to spatulas purchased
//		print_r($cart);
		foreach($cart as $foo){
			$id_spatula = $foo['id_spatula'];
//			echo $id_spatula;
			$query = "SELECT * FROM spatula_city.inventory WHERE fk_id_spatula = $id_spatula;";
			$inventory = query_to_array($query,$link);
			$inventory = $inventory[0];
			print_r($inventory);
			if($inventory['price_retail'] <= $inventory['price_sale']){
				$on_sale = 0;
			} else {
				$on_sale = 1;
			}
			$price = $inventory['price_sale'];
			
			$query = "INSERT INTO `spatula_city`.`spatula_has_purchase` (`fk_id_spatula`, `fk_id_purchase`, `price`, `on_sale`) VALUES ($id_spatula, $id_purchase, '$price', '$on_sale');";
			$link->query($query);
			echo $query;
		}
	}
?>