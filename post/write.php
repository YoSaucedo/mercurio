<?php 
	//borradores guardados
	$drafts = $conn->prepare("SELECT * FROM drafts WHERE author = ?");
	$drafts->execute([$_SESSION['user_id']]);
	if ($drafts->rowCount() != 0) { 
		if (isset($_GET['new'])) {
			include '../assets/php/post_form.php';
		} else { ?>
			<title>Borradores</title>
			<div class="drafts">
				<a class="single_draft" href="?new">
					<div class="draft_cover" style="background-image: url('../assets/img/control_panel.png');"></div>
					<h2>Nueva historia</h2>
					<p>Estos son tus borradores guardados, solo tú puedes verlos.</p>
					<p>También puedes empezar una nueva historia aquí.</p>
				</a>
				<?php while ($draft = $drafts->fetch()) {
					include '../assets/php/single_draft.php';
				} ?>
			</div>
		<?php }
	} else {
		include '../assets/php/post_form.php';
	}
?>
<script src="../assets/js/masonry.pkgd.min.js"></script>
<script src="../assets/js/infinite-scroll.pkgd.min.js"></script>
<script type="text/javascript">
	$('.drafts').masonry({
		// options
		itemSelector: '.single_draft',
		columnWidth: 260
	});
</script>