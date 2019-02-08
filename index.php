<?php 
	require_once 'config.php';
	include 'inc/php/header.php';
	if (isset($_GET['section'])) {
		include 'browse.php';
	} else {
		include 'front.php';
	}
	include 'inc/php/footer.php';
?>