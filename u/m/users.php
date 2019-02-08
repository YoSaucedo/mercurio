<?php 
$user = $_SESSION['username'];
$allusers = $conn->query("SELECT * FROM messages m1 WHERE stamp = (SELECT MAX(m2.stamp) FROM messages m2 WHERE m1.sender=m2.sender AND m1.receiver=m2.receiver) AND receiver = '$user' GROUP BY sender ORDER BY id DESC");
$getusers = $conn->query("SELECT * FROM messages m1 WHERE stamp = (SELECT MAX(m2.stamp) FROM messages m2 WHERE m1.sender=m2.sender AND m1.receiver=m2.receiver) AND receiver = '$user' GROUP BY sender ORDER BY id DESC LIMIT 7");
if (isset($_GET['all'])) {
	$users = $allusers;
} else {
	$users = $getusers;
} ?>
<h3>Tus conversaciones:</h3>
<div class="messages">
<?php
if ($getusers->rowCount() < 1) { ?>
	<p>Todavía no tienes conversaciones con nadie.</p>
<?php }
while ($m = $getusers->fetch()) { 
	if ($user != $m['sender']) {
		$sender = $m['sender'];
		$getavatar = $conn->query("SELECT avatar FROM users WHERE username = '$sender' LIMIT 1"); ?>
			<div class="with">
				<a href="<?php echo url()."/u/m/?with=".$m['sender']."#message".$m['id']; ?>">
					<img class="with" src="<?php echo url()."/u/".$getavatar->fetch()['avatar']; ?>">
					<?php
						$messages = $conn->query("SELECT * FROM messages WHERE receiver = '$user' AND sender = '$sender' AND unread = '1'");
						if ($messages->rowCount() > 0) { ?>
							<i class="im im-paperplane unread"></i>
						<?php }
					?>
				</a>
				<a class="with username" href="<?php echo url()."/u/?u=".$sender; ?>">@<?php echo mroExcerpt($sender, 7); ?></a>
			</div>
		<?php
	}
} if (!isset($_GET['all']) && $allusers->rowCount() > 7) { ?>
	<div class="with more">
		<a href="<?php echo url()."/u/m/?all"; ?>">
			<img class="with" src="<?php echo url()."/inc/img/plane.png"; ?>">
		</a>
		<a class="with username" href="<?php echo url()."/u/m/?all"; ?>">Ver todos</a>
	</div>
<?php } ?>
</div>