<!--Note: Currently, only the first page of search results displays correctly.  Subsequent pages will default back to not being 'filtered'.  This is because the links on the bottom are currently manually setting the address to page n with disregard to any filters that may be in place.  For what it's worth, this *should* change but is hardly a priority.-->

<?php 
	include 'header.php'; 
	include 'php_scripts/db_connect.php';
?>

<body>

<?php include 'nav_bar.php'; ?>

<div id='content'>

<form id='spatulaFilter' action="browse.php" method="get">
    <h3>Occasion</h3>
    <div>
    
    	<?php
		require 'php_scripts/db_connect.php';
		
		$query = 
		"SELECT * 
		FROM occassion 
		ORDER BY 'name' DESC;";
		
		$results = $link->query($query);
		
		while($row = $results->fetch_assoc()){
			if(in_array($row['id_occassion'],$_GET['filter']['occasion'])){
				echo "<input type='checkbox' name='filter[occasion][]' value='".$row['id_occassion']."' checked>".$row['name'];
			} else {
				echo "<input type='checkbox' name='filter[occasion][]' value='".$row['id_occassion']."' >".$row['name'];
			}
		}
		?>
    </div>
    <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>"> <!--Pass the page element as well so we can keep track of where we are-->
    <input type="submit" value="Filter">
</form>

<?php	
	require 'php_scripts/db_connect.php';
	
	function spatula_list($spatula_array, $page, $per_page)
	{
//		print_r($spatula_array);
//		$start = $per_page*($page-1);
		$start = 0;
//		$stop = $per_page*$page-1;
		$stop = count($spatula_array);
		$result = "<ul>";
		for ($i = $start; $i < $stop; $i++)
		{
			$result .= 
				"
				<li>
					<a href='spatula.php?id_spatula=".$spatula_array[$i]['id_spatula']."'>".$spatula_array[$i]['sp_name']."</a>
					<div>".$spatula_array[$i]['price_sale']."</div>
					<form action='php_scripts/form_handler.php' method='POST' class= 'cart_button'>
						<input type='hidden' name='add_to_cart[id_spatula]' value='".$spatula_array[$i]['id_spatula']."'>
						<input type='hidden' name='add_to_cart[sp_name]' value='".$spatula_array[$i]['sp_name']."'>
						<input type='submit' value='Add to Cart'>
					</form>
				</li>
				";
		}
		return $result."</ul>";
//		echo $result."</ul>";
	}

	$per_page = 10;
	$page_start = ($_GET['page']-1)*$per_page;
	
	$query = "
		SELECT sp_name, price_sale, id_spatula, price_retail, inv_count 
			FROM full_spatula 
			WHERE inv_count >  0 ";
	
	//Here, we're starting the fun of building up our "WHERE" statement to match the GET parameters
	foreach($_GET['filter'] as $category => $foo){
		if(!empty($foo))
		{
//			print_r($foo);
//			$category = key($foo);
//			echo $category;
			//Since the filter doesn't neccessarily match up with the key we'll be searching on, here's an array that will let us put things right.
			$filter_array = array
				(
				'occasion' => 'id_occassion'
				);
			
			$query .= " AND ( ";
			foreach($foo as $bar){
				$query .= " $filter_array[$category] = $bar OR ";
			}
			
			//query current has an " OR " tacked on the end.  This removes it.
			$query = substr($query,0,strlen($query)-4);
			$query .= " ) ";
		}
	}
	//Final bit of manipulation.  This part of the string sorts by price and returns the appropriate rows per page.
	$query .= " GROUP BY id_spatula
		ORDER BY price_sale 
		LIMIT $page_start, $per_page;";
	
//	echo $query;
		
	$result = $link->query($query);
	
	if ($result) 
		{
		/* fetch associative array */
		while ($row = $result->fetch_assoc()) 
			{
			$spatula[] = $row;
//			print_r($row);
			}
		/* free result set */
		$result->free();
		}
		
	$page = $_GET['page'];
	echo "<div>".spatula_list($spatula,$page,10)."</div><br />";
	echo "<div><a href='browse.php?page=".($page-1)."'><</a><a href='browse.php?page=".($page+1)."'>></a></div>";	//This needs to be changed to keep the last search.
?>
</div>
</body>

<?php include 'footer.php'; ?> 

<script type="text/javascript">
	$(document).ready(function(){
		$('.cart_button').submit(function( event ) {
//			alert('Add to Cart clicked');
			 // Stop form from submitting normally
			event.preventDefault();
			
			// Get some values from elements on the page:
			var $form = $( this ),
			url = $form.attr( "action" );
			
			// Send the data using post
			var posting = $.post( url , $form.serialize());
			
			//Grey out button to disallow same item being added to cart
			$(this).addClass('disabled');
			
//			var current = $('#cart_count').text();
//			current = 1 + parseInt(current);
//			$('#cart_count').text(current);

			var cart_update = $.post('nav_bar.php');
			cart_update.done(function(data){
				$('#navMenu').empty().append(data);
			});
		});
	});
</script> 
