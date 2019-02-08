<?php 
	require_once '../../config.php';

		if (!empty($_POST['section'])) {
			$section = $_POST['section'];
		} else {
			$section = false;
		} if (isset($_GET['add'])) {
			$class = 'subpost';
		} else {
			$class = 'post';
		}
		require_once '../inc/purifier/library/HTMLPurifier.auto.php';
		$purifier = new HTMLPurifier();
		$body = $purifier->purify($_POST['text_body']);

		//comprobar borrador
		$check = $conn->prepare("SELECT * FROM drafts WHERE id = ?");
		$check->execute([$_POST['draft_id']]);
		//nuevo borrador
		if ($check->rowCount() == 0) {
			$sql = $conn->prepare("INSERT INTO drafts (id, title, body, author, stamp, section, class) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$sql->execute([
				$_POST['draft_id'],
				$_POST['title'], 
				$body,
				$_SESSION['user_id'], 
				time(),
				$section,
				$class
			]);
		//actualizar borrador
		} else {
			$sql = $conn->prepare("UPDATE drafts SET title = ?, body = ?, stamp = ? WHERE id = ?");
			$sql->execute([
				$_POST['title'], 
				$body, 
				time(),
				$_POST['draft_id']
			]);
		}
?>