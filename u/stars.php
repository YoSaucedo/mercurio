<?php if (isset($_GET['stars'])) { ?>
	<title>Historias que te han gustado</title>
	<section class="content">
		<h2>Historias que te han gustado.</h2>
		<p>Aquí puedes ver todas las historias a las que le has dado una estrella. Usamos las estrellas como un indicador de la popularidad y el gusto de los usuarios por una historia.</p>
		<?php 
		if (isset($_GET['pageno'])) {
			$pageno = $_GET['pageno'];
		} else {
			$pageno = 1;
		}
		$limit = 12;
		$offset = ($pageno-1) * $limit;
		$getposts = $conn->prepare("SELECT * FROM posts WHERE id IN (SELECT post FROM stars WHERE star_from = ?)");
		$getposts->execute([$_SESSION['username']]);
		$total_rows = $getposts->rowCount();
		$total_pages = ceil($total_rows/$limit);
		$query = $conn->prepare("SELECT * FROM posts WHERE id IN (SELECT post FROM stars WHERE star_from = ?) ORDER BY id DESC LIMIT $offset, $limit");
		$query->execute([$_SESSION['username']]); ?>
		<div class="posts">
			<?php while ($post = $query->fetch()) { include '../inc/php/single_post.php'; } ?>		
		</div>
		<div class="page-load-status">
			<p class="infinite-scroll-request"><i class="im im-spinner" title="Cargando"></i></p>
			<p class="infinite-scroll-last"><i class="im im-archive" title="¡Has llegado al final!"></i></p>
			<p class="infinite-scroll-error"><i class="im im-cloud" title="¿Qué has hecho?"></i></p>
		</div>
		<ul class="hidden pagination">
		<?php
		if ($total_pages > 1) {
			$pageno = 0;
		    while ($pageno < $total_pages) { ?>
		    		<li <?php if (isset($_GET['pageno']) && $pageno+1 == $_GET['pageno']) { echo "class='current'"; } ?>>
		    			<a class="<?php if($_GET['pageno'] == $pageno+1){ echo "current"; } if ($pageno+1 !== 1 && $_GET['pageno'] < $pageno+1) { echo "pagination__next"; } ?>" href="?stars&pageno=<?php echo $pageno+1; ?>"><?php echo $pageno+1 ?></a>
		    		</li>
		    	<?php $pageno++; 
		    }
		} ?>
		</ul>
	</section>
	<script src="../inc/js/masonry.pkgd.min.js"></script>
	<script src="../inc/js/infinite-scroll.pkgd.min.js"></script>
	<script type="text/javascript">
		var $grid = $('.posts').masonry({
			itemSelector: '.single_post',
			columnWidth: 260
		});
		var msnry = $grid.data('masonry');
		$grid.infiniteScroll({
			path: '.pagination__next',
			append: '.posts',
			status: '.page-load-status',
			outlayer: msnry,
		});
	</script>
<?php } else { $query = $conn->prepare("SELECT * FROM posts WHERE id IN (SELECT post FROM stars WHERE star_from = ?) ORDER BY id DESC LIMIT 3");
	$query->execute([$_SESSION['username']]); ?>
	<div class="posts">
		<?php while ($post = $query->fetch()) { include '../inc/php/single_post_simple.php'; } ?>		
	</div>
<?php 
	if ($query->rowCount() != 0) { ?>
		<p><a href="?stars">Ver todas las historias.</a></p>
	<?php } else { ?>
		<p>Todavía no le has dado ninguna estrella a ninguna historia. <a href="../search.php">¿Por qué no lees un poco?</a></p>
	<?php }
} ?>