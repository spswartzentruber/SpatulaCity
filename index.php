<?php
include 'page_elements/header.php';
//session_start();
$_SESSION['cart'] = array();
?>

<body>
<div id="banner">
	<?php include 'login.php'; ?>
</div>
<div id="below_banner">
	<?php include 'page_elements/nav_bar.php'; ?>
    <div id='content'>
    	<p>For testing purposes, the uername "simon" with password "simon" may be used.  Currently, being logged in does nothing but give you access to the Account page (it will appear upon page refresh), which is currently nothing but a placeholder.</p>
    	<iframe width="560" height="315" src="//www.youtube-nocookie.com/embed/4BUDwj_mXKE?rel=0" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
</body>

<?php
include 'page_elements/footer.php';
?>