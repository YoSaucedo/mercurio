<?php 
	//borradores guardados
	$drafts = $conn->prepare("SELECT * FROM drafts WHERE author = ?");
	$drafts->execute([$_SESSION['user_id']]);
	if ($drafts->rowCount() != 0) { 
		if (isset($_GET['new'])) {
			include '../inc/php/post_form.php';
		} else { ?>
			<title>Borradores</title>
			<div class="drafts">
				<a class="single_draft" href="?new">
					<div class="draft_cover" style="background-image: url('../inc/img/control_panel.png');"></div>
					<h2>Nueva historia</h2>
					<p>Estos son tus borradores guardados, solo tú puedes verlos.</p>
					<p>También puedes empezar una nueva historia aquí.</p>
				</a>
				<?php while ($draft = $drafts->fetch()) {
					include '../inc/php/single_draft.php';
				} ?>
			</div>
		<?php }
	} else {
		include '../inc/php/post_form.php';
	}
?>
<script src="../inc/js/masonry.pkgd.min.js"></script>
<script src="../inc/js/infinite-scroll.pkgd.min.js"></script>
<script type="text/javascript">
	$('.drafts').masonry({
		// options
		itemSelector: '.single_draft',
		columnWidth: 260
	});
</script>