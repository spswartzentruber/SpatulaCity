<?php include 'header.php'; ?>

<body>

<?php
	session_start();
	include 'php_scripts/db_connect.php';
	include 'nav_bar.php';
	
	$id_spatula = $_GET['id_spatula'];
	
	$query = 
		"
		SELECT sp_name as 'name', sp_manufacturer as manufacturer, sp_color as color, image_url, info as description
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
<div class="nailthumb-container" id='spatula_image'><img src="/spatulacity/img/<?php echo $spatula_array['image_url'] ?>.jpg" / class="spatula_thumbnail"></div>
<h1 id='spatula_name'><?php echo $spatula_array['name'] ?></h1>
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
</body>

<?php include 'footer.php'; ?> 

 <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.nailthumb-container').nailthumb({width:300,height:300,method:'resize',fitDirection:'top left'});
	});
</script>