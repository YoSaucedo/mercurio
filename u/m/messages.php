<?php
	$with = $_GET['with'];
	$user = $_SESSION['username'];

	$query = $conn->query("SELECT * FROM messages WHERE sender = '$with' AND receiver = '$user' OR sender = '$user' AND receiver = '$with' ORDER BY id ASC");

	if ($query->rowCount() < 2) { ?>
		<p class="message advice">Para protegerte a ti y a <?php echo $with; ?> los mensajes privados no pueden ser borrados.</p>
	<?php } else {
		include 'users.php';
	}

	while ($m = $query->fetch()) {
		$avatar = $conn->prepare("SELECT avatar FROM users WHERE username = ?");
		$avatar->execute([$m['sender']]); ?>
		<div title="<?php echo $m['stamp']; ?>" class="message <?php if($m['sender'] == $user) { echo "out"; } else { echo "in"; } ?>" id="message<?php echo $m['id']; ?>">
			<a class="author" href="<?php echo url()."/u/?u=".$m['sender']; ?>">
				<img src="../<?php echo $avatar->fetch()['avatar']; ?>">
			</a>
			<p><?php echo nl2br(mroDecryption($m['body'])); ?></p>
		</div>
	<?php 
		if ($_SESSION['username'] != $m['sender']) {
			$read = $conn->prepare("UPDATE messages SET unread = '0' WHERE id = ?");
			$read->execute([$m['id']]);
			$notificate = $conn->prepare("DELETE FROM notifications WHERE user = ? AND target = ?");
			$notificate->execute([$_SESSION['username'], url()."/u/m/?with=".$m['sender']."#message".$m['id']]);
		}
	}
?>
<title>Tu conversación con <?php echo $_GET['with']; ?></title>
<div class="parent">
<form class="child comment" action="" method="POST">
	<textarea id="fixer" name="message_body" placeholder="Escribe aquí tu mensaje para <?php echo $_GET['with']; ?>"></textarea>
	<button type="submit" name="message">Enviar mensaje</button>
	<script type="text/javascript">
		function toggleFixed() {
		   adjustWidth();
		   $(".child").toggleClass("fixed");
		 }

		 function adjustWidth() {
		   var parentwidth = $(".parent").width();
		   $(".child").width(parentwidth);
		 }
		 $(function() {
		   $("#fixer").click(
		     function() {
		       toggleFixed();
		     });
		   $(window).resize(
		     function() {
		       adjustWidth();
		     })
		 })

	</script>
</form>
</div>