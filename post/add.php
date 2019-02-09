<?php 
	$query = $conn->prepare("SELECT * FROM posts WHERE id = ?");
	$query->execute([$_GET['add']]);

	if ($query->rowCount() != 0 && $_SESSION['user_id'] == $query->fetch()['author']) {
		include '../assets/php/add_form.php';
	} else {
		header('location:'.url()."/post/");
	}
?>