<?php 
	$query = $conn->prepare("SELECT * FROM drafts WHERE id = ?");
	$query->execute([$_GET['draft']]);

	if ($query->rowCount() != 0) {
		while ($draft = $query->fetch()) {
			if ($draft['author'] == $_SESSION['user_id']) {
				if ($draft['class'] == 'post') {
					include '../assets/php/edit_draft.php';
				} elseif ($draft['class'] == 'subpost') {
					include '../assets/php/edit_draft_add.php';
				}
			} else {
				header('location:'.url()."/post/");
			}
		}
	} else {
		header('location:'.url()."/post/");
	}
?>