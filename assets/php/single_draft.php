<a class="single_draft" href="?draft=<?php echo $draft['id']; ?>">
	<?php 
		if (!empty($draft['cover'])) { ?>
			<div class="draft_cover" style="background-image: url(<?php echo $draft['cover']; ?>);"></div>
		<?php }
	?>
	<h2><?php echo $draft['title']; ?></h2>
	<p><?php echo mroExcerpt($draft['body'], 333); ?></p>
	<time><?php echo date('h:i:s d/m/y', (int)$draft['stamp']); ?></time>
</a>