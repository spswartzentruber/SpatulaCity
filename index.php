<?php
include 'page_elements/header.php';
//session_start();
$_SESSION['cart'] = array();
?>

<body>
<div id="banner">
	<?php include 'page_elements/login.php'; ?>
</div>
<div id="below_banner">
	<?php include 'page_elements/nav_bar.php'; ?>
    <div id='content'>
    	<iframe width="560" height="315" src="//www.youtube-nocookie.com/embed/4BUDwj_mXKE?rel=0" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
</body>

<?php
include 'page_elements/footer.php';
?>