<?php session_start(); ?>
<div id="navMenu" class="navigation">
    <ul>
        <li class="single-menu-item"><a href="/spatulacity/index.php">About</a></li>
        <li class="single-menu-item"><a href="/spatulacity/browse.php?page=1">Browse</a></li>
        <li class="single-menu-item"><a href="/spatulacity/cart.php">Cart [<?php echo count($_SESSION['cart']); ?>]</a></li>
        <li class="single-menu-item"><a href="/spatulacity/create_spatula.php">Create Spatula</a></li>
        <?php
		if(isset($_SESSION['username']))
		{
			echo "<li><a href='/spatulacity/account.php'>Account</a></li>";
		}
		?>
    </ul>
</div>