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
?>