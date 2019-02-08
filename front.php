<title><?php echo mroName(); ?></title>
<section class="front first">
	<div class="wrapper">
		<h1>Bienvenido a <?php echo mroName(); ?>.</h1>
		<p>¿Qué te apetece leer hoy?</p>
		<div class="sections">
			<?php $sections = $conn->query("SELECT * FROM sections ORDER BY front DESC");
			while ($section = $sections->fetch()) { ?>
				<a href="#<?php echo $section['name']; ?>">
					<span class="section" style="background-color: #<?php echo $section['color']; ?>;"><?php echo $section['name']; ?></span>
				</a>
			<?php } ?>
		</div>
	</div>
</section>
<section class="content">
<?php $sections = $conn->query("SELECT * FROM sections ORDER BY front DESC");
	while ($section = $sections->fetch()) { ?>
	<section class="front" style="border-top: 1em solid #<?php echo $section['color']; ?>; background-image: url(<?php echo $section['cover']; ?>);" id="<?php echo $section['name']; ?>">
	<div class="wrapper">
		<a href="?section=<?php echo $section['slug']; ?>"><h2><?php echo $section['name']; ?></h2></a>
			<?php 
			$week = time() - 604800;
			$posts = $conn->query("SELECT * FROM posts WHERE section = '$section[id]' AND updated_m != 'nochange' AND updated > '$week' ORDER BY front DESC, pop DESC LIMIT 3");
			if ($posts->rowCount() < 3) {
				$alltime = $conn->query("SELECT * FROM posts WHERE section = '$section[id]' AND updated_m != 'nochange' ORDER BY front DESC, pop DESC, id DESC LIMIT 3"); ?>
				<div class="posts">
					<?php while ($post = $alltime->fetch()) { include 'inc/php/single_post.php'; } ?>
				</div>
				<?php if ($alltime->rowCount() == 0) { ?>
					<p>Esta sección aún no tiene contenido.</p>
					<p>Aprovecha para ser el primero en <a href="/post/?section=<?php echo $section['name']; ?>">publicar</a>.</p>
				<?php }
			} else { ?>
				<div class="posts">
					<?php while ($post = $posts->fetch()) { include 'inc/php/single_post.php'; } ?>
				</div>
			<?php } ?>
	</div>
	<div class="gradient"></div>
</section>
<?php } ?>
<script src="inc/js/masonry.pkgd.min.js"></script>
<script type="text/javascript">
	$('.posts').masonry({
		// options
		itemSelector: '.single_post',
		columnWidth: 260
	});
</script>