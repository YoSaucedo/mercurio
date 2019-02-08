<?php 
	$query = $conn->prepare("SELECT * FROM posts WHERE id = ?");
	$query->execute([$_GET['edit']]);

	if ($query->rowCount() != 0) {
		while ($post = $query->fetch()) {
			if ($post['author'] == $_SESSION['user_id']) {
				if (isset($_GET['and'])) {
					include '../inc/php/edit_add_form.php';
				} else {
					include '../inc/php/edit_form.php';
				}
			} else {
				include 'write.php';
			}
		}
	} else {
		header('location:'.url()."/post/");
	}
?>