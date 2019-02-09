<?php $pid = microtime(); $pid = explode(' ', $pid); $pid = $pid[1] + $pid[0]; $start = $pid; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/assets/css/icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/assets/css/styles.css">
	<link rel="icon" href="<?php echo url(); mroLogo(); ?>"/>
	<meta property="og:title" content="<?php mroName(); ?>"/>
	<meta name="description" content="Lee y escribe historias interesantes todos los días en Mercurio.">
	<meta property="og:description" content="Lee y escribe historias interesantes todos los días en Mercurio.">
	<meta property="og:url" content="<?php echo url(); ?>" />
	<meta property="og:image" content="<?php echo url(); mroLogo(); ?>">
	<meta property="og:locale:alternate" content="es_ES" />
	<script src="<?php echo url(); ?>/assets/js/jquery-3.3.1.min.js"></script>
	<?php include 'styles.php'; 
	include 'navbar.php'; ?>