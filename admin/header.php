<?php $pid = microtime(); $pid = explode(' ', $pid); $pid = $pid[1] + $pid[0]; $start = $pid; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/assets/css/icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/assets/css/styles.css">
	<link rel="icon" href="<?php echo url(); mroLogo(); ?>"/>
	<script src="<?php echo url(); ?>/assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo url(); ?>/assets/js/jscolor.js"></script>
	<?php include '../assets/php/styles.php'; 
	include 'navbar.php'; ?>