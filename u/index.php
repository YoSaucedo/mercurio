<?php 
	require_once '../config.php';
	include '../inc/php/header.php';
	if (isset($_GET['password'])) {
		include 'password.php';
	} elseif (isset($_GET['u'])) {
		include 'author.php';
	} elseif (uUser() && isset($_GET['welcome'])) {
		include 'welcome.php';
	} elseif (uUser() && isset($_GET['edit'])) {
		include 'edit.php';
	} elseif (uUser() && isset($_GET['feed'])) {
		include 'feed.php';
	} elseif (uUser() && isset($_GET['stars'])) {
		include 'stars.php';
	} elseif (uUser()) {
		include 'user.php';
	} else { ?>
		<section class="login">
			<?php include 'login.php';
			include 'register.php'; ?>
		</section>
	<?php }
	include '../inc/php/footer.php';
?>