<!--Note: Searching does not restart to page 1.-->

<?php 
	include_once 'page_elements/header.php'; 
	include_once 'db_connect.php';
?>

<body>
<div id="banner">
	<?php 
	include 'page_elements/banner.php';
	include 'login.php'; 
	?>
</div>
<div id="below_banner">
<?php include 'page_elements/nav_bar.php'; ?>

<div id='content'>
    <div id ='filter_list'>
		<form id='spatulaFilter' action="browse.php" method="get">
			<ul>
                <li>
                    <h3>Occasion</h3>
                    <div class="filter_checkboxes">
                        <?php                        
                        $query = 
                        "SELECT * 
                        FROM occassion 
                        ORDER BY 'name' DESC;";
                        
                        $results = $link->query($query);
                        
                        while($row = $results->fetch_assoc()){
                            if(in_array(!empty($_GET['filter']['occasion']) and $row['id_occassion'],$_GET['filter']['occasion'])){
                                echo "<input type='checkbox' name='filter[occasion][]' value='".$row['id_occassion']."' checked>".$row['name'];
                            } else {
                                echo "<input type='checkbox' name='filter[occasion][]' value='".$row['id_occassion']."' >".$row['name'];
                            }
                        }
                        ?>
                    </div>
                </li>
                <li>
                    <h3>Color</h3>
                    <div class="filter_checkboxes">
                        <?php
                        
                        $query = 
                        "SELECT sp_color as color
                        FROM spatula
						GROUP BY color 
                        ORDER BY color DESC;";
                        
                        $results = $link->query($query);
                        
                        while($row = $results->fetch_assoc()){
                            if(in_array($row['color'],$_GET['filter']['color'])){
                                echo "<input type='checkbox' name='filter[color][]' value='".$row['color']."' checked>".$row['color'];
                            } else {
                                echo "<input type='checkbox' name='filter[color][]' value='".$row['color']."' >".$row['color'];
                            }
                        }
                        ?>
                    </div>
                </li>
            </ul>
            <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>"> <!--Pass the page element as well so we can keep track of where we are-->
            <input type="submit" value="Filter">
        </form>
    </div>
	<?php
	function spatula_list($spatula_array, $page, $per_page)
	{
//		print_r($spatula_array);
		$start = 0;
		$stop = count($spatula_array);
		$result = "
			<table id='spatula_results'>
				<tr>
					<th>Name</th>
					<th>Price</th>
					<th></th>
				</tr>
			";
		for ($i = $start; $i < $stop; $i++)
		{
			$result .= 
				"
				<tr>
					<td class='spatula_name_col'><a href='spatula.php?id_spatula=".$spatula_array[$i]['id_spatula']."'>".$spatula_array[$i]['sp_name']."</a></td>
					<td class='spatula_price_col'>".$spatula_array[$i]['price_sale']."</td>
					<td class='spatula_cart_col'>
						<form action='form_handler.php' method='POST' class= 'cart_button'>
							<input type='hidden' name='add_to_cart[id_spatula]' value='".$spatula_array[$i]['id_spatula']."'>
							<input type='hidden' name='add_to_cart[sp_name]' value='".$spatula_array[$i]['sp_name']."'>
							<input type='submit' value='Add to Cart'>
						</form>
					</td>
				</tr>
				";
		}
		return $result."</table>";
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
			//Since the filter doesn't necessarily match up with the key we'll be searching on, here's an array that will let us put things right.
			$filter_array = array
				(
				'occasion' => 'id_occassion'
				,'color' => 'sp_color'
				);
			
			$query .= " AND ( ";
			foreach($foo as $bar){
				$query .= " $filter_array[$category] = '$bar' OR ";
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
	echo "<div id='spatula_list'>".spatula_list($spatula,$page,10)."</div><br />";
	
	$query_arr = $_GET;
	$query_arr["page"] = $query_arr["page"] + 1;
	$next_page_url = http_build_query($query_arr);
	
	$query_arr["page"] = min(array(1,$query_arr["page"] - 2));	//Go back 2 since we already added one for the next page.  Min of 1 so we don't go too low.
	$previous_page_url = http_build_query($query_arr);
	
	echo "<div><a href='browse.php?$previous_page_url'><</a><a href='browse.php?$next_page_url'>></a></div>";	//This needs to be changed to keep the last search.
	?>
</div>
</div>
</body>

<?php include 'page_elements/footer.php'; ?> 

<script type="text/javascript">
$(document).ready(function(){	
	$('.filter_checkboxes').hide();

	$('.cart_button').submit(function( event ) {
		console.log('Add to Cart clicked');
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
	
	$('#filter_list h3').click(function(){
		console.log('list element clicked');
		$(this).siblings('.filter_checkboxes').toggle("fast");
		
		$('#filter_list li').toggleClass("expanded");
	});
});
</script> 
