<a href="<?php echo url()."/u/?u=".$user['username']; ?>">
<div class="author">
	<img src="<?php echo url()."/u/".$user['avatar']; ?>">
	<?php if (!empty($user['name'])) { ?>
		<p class="name"><?php echo mroExcerpt($user['name'], 13); ?></p>
	<?php } ?>
	<p class="arroba">@<?php echo $user['username']; ?></p>
	<p class="role"><?php if ($user['role'] == '1') { echo "admin"; } ?></p>
</div>
</a>