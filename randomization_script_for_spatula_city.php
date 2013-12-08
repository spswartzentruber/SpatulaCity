<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
include 'php_scripts/db_connect.php';

date_default_timezone_set('America/Indianapolis'); 

class rand_user{
	public function rand_area_code(){
		return mt_rand(100,999);
	}

	public function rand_phone_num_no_area(){
		return mt_rand(1000000,9999999);
	}
	
	public function rand_name($name_array){
		return $name_array[array_rand($name_array)];
	}
	
	public function username($f_name,$l_name){
		return strtolower($this->f_name.'_'.$this->l_name).mt_rand(0,9999);
	}
	
	public function pass_hash(){
	}
	
	function __construct($f_name_gender_neutral_array, $l_nameArray){
		$this->area_code = $this::rand_area_code();
		$this->phone_num = $this::rand_phone_num_no_area();
		$this->f_name = $this::rand_name($f_name_gender_neutral_array);
		$this->l_name = $this::rand_name($l_nameArray);
		$this->username = $this::username($this->f_name,$this->l_name);
		$this->email = $this->username.'@fakemail.com';
		$this->password = $this->username;
	}
}

class rand_spatula{
	public function rand_lipsum($amount = 1, $what = 'paras', $start = 0) {
		//Taken from http://blog.ergatides.com/2011/08/16/simple-php-one-liner-to-generate-random-lorem-ipsum-lipsum-text/
		return simplexml_load_file('http://www.lipsum.com/feed/xml?amount=$amount&what=$what&start=$start')->lipsum;
	}
	
	public function rand_retail_price($max_price){
		return mt_rand(2,$max_price*100)/100;
	}
	
	public function rand_sale_price($retail_price){
		if (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() > 0.75){
			return mt_rand(1,$retail_price*100)/100;
		} else {
			return $retail_price;
		}
	}
	
	public function rand_img_url(){
		$temp = 'img\ ';
		return trim($temp).mt_rand(1,50);
	}
	
	public function rand_spat_name($arr1,$arr2){
		if (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() > 0.5){
			$name = $arr1[array_rand($arr1)].' '.$arr2[array_rand($arr2)];
		} else {
			$name = $arr2[array_rand($arr2)].' '.$arr1[array_rand($arr1)];
		}
		
		if (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() > 0.25){
			return $name.' '.mt_rand(1,100)*100;
		}
		
		return $name;
	}
	
	public function rand_color($color_array){
		return $color_array[array_rand($color_array)];
	}
	
	public function rand_manu($manu_array){
		return $manu_array[array_rand($manu_array)];
	}
	
	function __construct($f_name_part,$adjative,$color_array,$manu_array){
		$this->info = $this::rand_lipsum(50,'words');
		$this->retail_price = $this::rand_retail_price(100);
		$this->sale_price = $this::rand_sale_price($this->retail_price);
		$this->img_url = $this::rand_img_url();
		$this->name = $this::rand_spat_name($f_name_part,$adjative);
		$this->color = $this::rand_color($color_array);
		$this->manufacturer = $this::rand_manu($manu_array);
	}
}

class rand_address{
	public function rand_street_address($sn, $suffix){
		$num = mt_rand(1,9999);
		switch ( mt_rand(1,20) ){
			case 1:
				$dir = 'N';
				break;
			case 2:
				$dir = 'S';
				break;
			case 3:
				$dir = 'E';
				break;
			case 4:
				$dir = 'W';
				break;
			default:
				$dir = '';
		} 
		$street = $sn[array_rand($sn)];
		$suff = $suffix[array_rand($suffix)];
		
		return $num.' '.$dir.' '.$street.' '.$suff;
	}
	
	public function rand_zip(){
		return mt_rand(10000,99999);
	}

	public function rand_state($states){
		return $states[array_rand($states)];
	}
	
	public function rand_city($city_array){
		return $city_array[array_rand($city_array)];
	}
	
	function __construct($us_state_abbrevs_names, $street_names, $street_suffix,$city_array){
		$this->zip = $this::rand_zip();
		$this->city = $this::rand_city($city_array);
		$this->state = $this::rand_state($us_state_abbrevs_names);
		$this->street = $this::rand_street_address($street_names, $street_suffix);
		
	}
}

function rand_timestamp($min_date,$max_date){
	//Taken from http://stackoverflow.com/questions/1972712/generate-random-date-between-two-dates-using-php
	
	// Convert to timestamps
    $min = strtotime($min_date);
    $max = strtotime($max_date);

    // Generate random number using above bounds
    $val = mt_rand($min, $max);

    // Convert back to desired date format
    return date('Y-m-d H:i:s', $val);
}

function fetch_id_array($table, $id_col, $db){
//	echo "we're in";
	$query = "SELECT ".$id_col." FROM ".$table.";";
	$results = $db->query($query);
//	echo "query done";
	while($row = $results->fetch_array(MYSQLI_NUM)){
//		print_r($row);
		$results_array[] = $row[0];
	}
	return $results_array;
}

function decider($prob){
	//This function will return True if above the $prob, False if below.  Pretty simple.
	if(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() > $prob){
		return true;
	} else {
		return false;
	}
}

function randomize_user_has_address($link, $percent){
	//Populate user_has_address table.  This depends on the user and address tables already being populated.
	$username_array = fetch_id_array('user','username', $link);
	$address_array = fetch_id_array('address','id_address', $link);
	$name_array = fetch_id_array('name','id_name', $link);
	
//	print_r($username_array);
	
	foreach($username_array as $username){
		//Let's decide if the user has an address on record
		if(decider($percent)){
			//Was going to put the first adddress as the user in question but screw it.  I'd have to change too much
			$address_num = mt_rand(1,4);
			for ($i=0; $i<$address_num; $i++){
				$address_fk = $address_array[array_rand($address_array)];
				$name_fk = $name_array[array_rand($name_array)];
				
				$query = "INSERT INTO `spatula_city`.`user_has_address` (`fk_username`, `fk_id_address`, `fk_id_name`) VALUES ('".$username.",', '".$address_fk."', '".$name_fk."');";
				$link->query($query);
			}
		}
	}
}

function randomize_purchase($entry_num, $link){
	//Create data of purchases.  Dependent on address, user and name tables
	$address_array = fetch_id_array('address','id_address', $link);
	$user_array = fetch_id_array('user','username', $link);
	$delivery_service_array = fetch_id_array('delivery_service','id_delivery_service', $link);
	$payment_method_array = fetch_id_array('payment_method','id_payment_method', $link);
	
	for ($i=0; $i<$entry_num; $i++){
		$user = $user_array[array_rand($user_array)];
		$delivery_service = $delivery_service_array[array_rand($delivery_service_array)];
		$payment_method = $payment_method_array[array_rand($payment_method_array)];
		$address = $address_array[array_rand($address_array)];
		
		if(decider(0.5) == true){
			$query = "INSERT INTO `spatula_city`.`purchase` ( `fk_id_address`, `fk_user_username`, `purchase_time`, `fk_id_delivery_service`, `fk_id_payment_method`) VALUES (".$address.", '".$user."', '".rand_timestamp('1990-1-1','2013-1-1')."', ".$delivery_service.", ".$payment_method.");";
			} else {
			$query = "INSERT INTO `spatula_city`.`purchase` ( `fk_id_address`, `fk_user_username`, `purchase_time`, `fk_id_delivery_service`, `fk_id_payment_method`) VALUES (".$address.", 'no_username', '".rand_timestamp('1990-1-1','2013-1-1')."', ".$delivery_service.", ".$payment_method.");";
//			echo "we're false";
		}
//		echo $query;
		$link->query($query);
	}
}

function randomize_inventoy($link){
	$spatula_array = fetch_id_array('spatula','id_spatula',$link);
	foreach($spatula_array as $val){
		$spatula_price = new rand_spatula(array(''),array(''),array(''),array(''));
		$inventory_quant = mt_rand(100,1000);
		$date = rand_timestamp('1990-1-1','2013-1-1');
		
		$query = "INSERT INTO `spatula_city`.`inventory` (`fk_id_spatula`, `price_retail`, `price_sale`, `inv_count`, `last_update`) VALUES ('".$val."', ".$spatula_price->retail_price.", ".$spatula_price->sale_price.", ".$inventory_quant.", '".$date."');";
		$link->query($query);
	}
}

function randomize_spatula_has_purchase($link){
	$purchase_array = fetch_id_array('purchase','id_purchase',$link);
	
	$query = "SELECT fk_id_spatula, price_retail, price_sale FROM inventory;";
	$results = $link->query($query);
	while($row = $results->fetch_assoc()){
		$spatula_inventory_array[] = $row;
	//	print_r($row);
	}
		
	foreach($purchase_array as $foo){
	//	echo "foo is ".$foo." ";
		$num_spatulas_purchased = mt_rand(1,10);
		$spatulas_purchased = array_rand($spatula_inventory_array,$num_spatulas_purchased);
	//	print_r($spatulas_purchased);
		foreach($spatulas_purchased as $bar){
	//		echo "bar is ";
	//		print_r($bar);
	//		print_r($spatula_inventory_array[$bar]);
			if($spatula_inventory_array[$bar]['price_retail'] == $spatula_inventory_array[$bar]['price_sale']){
				$sale = 0;
			} else {
				$sale = 1;
			}
			$query = "INSERT INTO `spatula_city`.`spatula_has_purchase` (`fk_id_spatula`, `fk_id_purchase`, `price`, `on_sale`) VALUES ('".$spatula_inventory_array[$bar]['fk_id_spatula']."', '".$foo."', '".$spatula_inventory_array[$bar]['price_retail']."', '".$sale."');";
	//		echo $query;
			$link->query($query);
		}
	}
}

//Arrays to pull randomness from
$us_state_abbrevs_names = array(
	'AL',
	'AK',
	'AS',
	'AZ',
	'AR',
	'CA',
	'CO',
	'CT',
	'DE',
	'DC',
	'FM',
	'FL',
	'GA',
	'GU',
	'HI',
	'ID',
	'IL',
	'IN',
	'IA',
	'KS',
	'KY',
	'LA',
	'ME',
	'MH',
	'MD',
	'MA',
	'MI',
	'MN',
	'MS',
	'MO',
	'MT',
	'NE',
	'NV',
	'NH',
	'NJ',
	'NM',
	'NY',
	'NC',
	'ND',
	'MP',
	'OH',
	'OK',
	'OR',
	'PW',
	'PA',
	'PR',
	'RI',
	'SC',
	'SD',
	'TN',
	'TX',
	'UT',
	'VT',
	'VI',
	'VA',
	'WA',
	'WV',
	'WI',
	'WY',
	'AE',
	'AA',
	'AP'
);

$street_names = array(
	'Second',
	'Third',
	'First',
	'Fourth',
	'Park',
	'Fifth',
	'Main',
	'Sixth',
	'Oak',
	'Seventh',
	'Pine',
	'Maple',
	'Cedar',
	'Eighth',
	'Elm',
	'View',
	'Washington',
	'Ninth',
	'Lake',
	'Hill'
);

$street_suffix = array(
	'Aly',
	'Anx',
	'Arc',
	'Blvd',
	'Brg',
	'Ctr',
	'Cir',
	'Ln',
	'Pkwy',
	'Rdg',
	'Riv',
	'Rd',
	'Row',
	'Sq',
	'St',
	'Ter'
);

		$f_name_part = array(
	'spatula',
	'flipper',
	'turner',
	'scraper',
	'kitchen assistant',
	'filling knife',
	'frosting spatula'
);

$adjative = array(
	'adventurous',
	'courageous',
	'faithful',
	'fearless',
	'forceful',
	'pioneering',
	'plucky',
	'romantic',
	'willing',
	'tough',
	'reliable',
	'witty',
	'gentle',
	'good',
	'gregarious',
	'hard-working',
);

$f_nameMaleArray = array("Jacob","Ethan","Michael","Jayden","William","Alexander","Noah","Daniel","Aiden","Anthony",
					 "Joshua","Mason","Christopher","Andrew","David","Matthew","Logan","Elijah","James","Joseph",
					 "Gabriel","Benjamin","Ryan","Samuel","Jack","John","Nathan","Christian", "Liam","Dylan",
					 "Landon","Caleb","Tyler","Lucas","Evan","Gavin","Nicholas","Isaac","Brayden","Luke",
					 "Angel","Brandon","Isaiah","Jorden","Owen","Carter","Connor","Justin","Jose","Jeremiah");

$f_nameFemaleArray = array("Isabella","Sophia","Emma","Olivia","Ava","Emily","Abigail","Madison","Chloe","Mia",
						   "Addison","Elizabeth","Ella","Natalie","Samantha","Alexis","Lily","Grace","Hailey",
						   "Alyssa","Lillian","Trillian","Hannah","Avery","Leah","Navaeh","Sofia","Ashley","Anna",
						   "Brianna","Sarah","Zoe","Victoria","Gabriella","Brooklyn","Kaylee","Taylor","Layla",
						   "Allison","Evelyn","Riley","Amelia","Khloe","Makayla","Aubrey","Charlotte","Savannah",
						   "Zoey","Bella","Alexa");

$l_nameArray = array("Smith","Johnson","Williams","Jones","Brown","Davis","Miller","Wilson","Moore","Taylor",
					 "Anderson","Thomas","Jackson","White","Harris","Martin","Thompson","Garcia","Martinez",
					 "Robinson","Clark","Rodriguez","Lewis","Lee","Walker","Hall","Allen","Young","Hernandez",
					 "King","Wright","Lopez","Hill","Scott","Green","Adams","Baker","Gonzalez","Nelson","Carter",
					 "Mitchell","Perez","Roberts","Turner","Phillips","Cambell","Parker","Evans","Collins","Stewart",
					 "Sanchez","Morris","Rogers","Reed","Cook","Morgan","Bell","Murphy","Bailey","Rivera",
					 "Cooper","Richardson","Cox","Howard","Ward","Torres","Peterson","Gray","Ramirez","James",
					 "Watson","Brooks","Kelly","Sanders","Price","Bennett","Wood","Barnes","Ross","Henderson",
					 "Coleman","Jenkins","Perry","Powell","Long","Patterson","Hughes","Flores","Washington","Butler",
					 "Simmons","Foster","Bryant","Russel","Hayes","Ford","Hamilton","Graham","Sullivan","Cole");

$f_name_gender_neutral_array = array_merge($f_nameFemaleArray,$f_nameMaleArray);

$common_city_array = array(
	'Clinton',
    'Franklin',
    'Madison',
    'Washington',
    'Chester',
    'Marion',
    'Greenville',
    'Springfield',
    'Georgetown',
    'Salem',
	'Newport',
	'Dover ',
	'Riverside',
	'Oakland',
	'Mount Vernon',
	'Kingston',
	'Hudson',
	'Cleveland',
	'Auburn',
	'Winchester',
	'Milford',
	'Lexington',
	'Dayton',
	'Clayton',
	'Centerville',
	'Milton',
	'Manchester',
	'Burlington',
	'Jackson'
);

$colors = array(
	'Ivory',
	'Beige',
	'Wheat',
	'Tan',
	'Khaki',
	'Silver',
	'Gray',
	'Charcoal',
	'Navy Blue',
	'Royal Blue',
	'Medium Blue',
	'Azure',
	'Cyan',
	'Aquamarine',
	'Teal',
	'Forest Green',
	'Olive',
	'Chartreuse',
	'Lime',
	'Golden',
	'Goldenrod',
	'Coral',
	'Salmon',
	'Hot Pink',
	'Fuchsia',
	'Puce',
	'Mauve',
	'Lavender',
	'Plum',
	'Indigo',
	'Maroon',
	'Crimson'	
);

$colors = array_map(strtolower,$colors);

$spat_manu = array('Beknown LLC','Full Circle','Tristar Products, Inc.','Scanpan USA Inc.','Kitchen Innovations Inc.','Chronicle Books','Neoflam','Urbio LLC','Punkt','Clipper Corp.','Regency Wraps, Inc.','Maxs Wholesale Imp. Exp., Inc.','Kai USA','Light and Contrast','Planit Products Ltd.','Kuhn Rikon Corp.','soireehome','Housewares International Inc.','Amoretti Brothers','Prodyne Enterprises Inc.');

//Loops to generate INSERT statements

//Populate address table.  Dependent on State table already being populated
/*
for($i=0; $i  < 200; $i++) {
	$foo = new rand_address($us_state_abbrevs_names,$street_names,$street_suffix,$common_city_array);
	echo "INSERT INTO `spatula_city`.`address` (`street`, `city`, `zip`, `fk_id_state`) VALUES ('".$foo->street."', '".$foo->city."', ".$foo->zip.", ".mt_rand(1,62).");";
}
*/

//Populate the spatula table.
/*
for($i=0; $i  < 50; $i++) {
	$foo = new rand_spatula($f_name_part,$adjative,$colors,$spat_manu);
	echo "INSERT INTO `spatula_city`.`spatula` (`sp_name`, `sp_manufacturer`, `sp_color`, `create_time`, `image_url`, `info`) VALUES ('".$foo->name."', '".$foo->manufacturer."', '".$foo->color."','".rand_timestamp('1990-1-1','2013-1-1')."', '".$foo->img_url."', '".$foo->info."');";
}
*/

//Populate name and user tables
/*
for($i=0; $i  < 200; $i++) {
	$foo = new rand_user($f_name_gender_neutral_array,$l_nameArray);
	$query = "INSERT INTO `spatula_city`.`name` (`f_name`, `l_name`) VALUES ('".$foo->f_name."', '".$foo->l_name."');";
	$link->query($query);
	$foo->name_id = $link->insert_id;
	//echo $foo->name_id;
	$query = "INSERT INTO `spatula_city`.`user` (`username`, `email`, `password`, `create_time`, `fk_id_name`) VALUES ('".$foo->username."', '".$foo->email."', '".$foo->password."', '".rand_timestamp('1990-1-1','2013-1-1')."', '".$foo->name_id."');";
	$link->query($query);
}
*/

//Populate user_has_phone table.  Depends on both phone and user tables being populated
/*
$query = "SELECT * FROM phone";
$results = $link->query($query);
$phone_count = $results->num_rows;
//echo "phone rows: ".$phone_count."\n";

$query = "SELECT * FROM user";
$results = $link->query($query);
while($row = $results->fetch_array(MYSQLI_NUM)){
	$username_array[] = $row[0];
}
//print_r($username_array);

$phone_user_array = array();
for($i=0; $i  < 50; $i++) {
	$phone_user_array[] = 
		array(
			'phone_id' => mt_rand(1,$phone_count),
			'user_id' => $username_array[array_rand($username_array)]
		);
}
//print_r($phone_user_array);
//$phone_user_array = array_unique($phone_user_array);
//print_r($phone_user_array);

foreach($phone_user_array as $val){
	$query = "INSERT INTO `spatula_city`.`user_has_phone` (`fk_ck_username`, `fk_ck_id_phone`) VALUES ('".$val['user_id']."', ".$val['phone_id'].");";
	$link->query($query);
}
*/

//Poplate spatula_has_occassion table.  Depends on occassion and spatula tables being popluated
/*
$query = "SELECT id_occassion FROM occassion";
$results = $link->query($query);
while($row = $results->fetch_array(MYSQLI_NUM)){
	$occassion_array[] = $row[0];
}

$query = "SELECT id_spatula FROM spatula;";
$results = $link->query($query);
while($row = $results->fetch_array(MYSQLI_NUM)){
	$spatula_array[] = $row[0];
}

foreach($spatula_array as $id_spat){
	$rand_occassions = array_rand($occassion_array,mt_rand(1,count($occassion_array)));
	foreach($rand_occassions as $id_occa){
		$query = "INSERT INTO `spatula_city`.`spatula_has_occassion` (`fk_ck_id_spatula`, `fk_ck_id_occassion`) VALUES (".$id_spat.",".$spatula_array[$id_occa].");";
		$link->query($query);
	}
}
*/

//randomize_user_has_address($link, .5);
//randomize_purchase(200,$link);
//randomize_inventoy($link);
//randomize_spatula_has_purchase($link);

mysqli_close($link);
?>
</body>
</html>
	