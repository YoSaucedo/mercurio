<?php require_once '../config.php';
include 'header.php';
if (!uAdmin()) {
	header('location: '.url());
} ?>
<section class="content">
	<?php if (isset($_GET['sections'])) {
		include 'sections.php';
	} elseif (isset($_GET['section'])) {
		include 'section.php';
	} elseif (isset($_GET['post'])) {
		include 'post.php';
	} elseif (isset($_GET['front'])) {
		include 'front.php';
	} elseif (isset($_GET['config'])) {
		include 'site.php';
	} elseif (isset($_GET['user'])) {
		include 'user.php';
	} else {
		include 'admin.php';
	} ?>
</section>
<?php include '../inc/php/footer.php'; ?>