<title>Feed</title>
<section class="content">
<?php 
	$username = $_SESSION['username'];
	if (isset($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	} else {
		$pageno = 1;
	}
	$limit = 12;
	$offset = ($pageno-1) * $limit;
	$getposts = $conn->query("SELECT id FROM posts WHERE author IN ( SELECT feed_to FROM feed WHERE feed_from = '$_SESSION[user_id]' )");
	$total_rows = $getposts->rowCount();
	$total_pages = ceil($total_rows/$limit);
	$query = $conn->query("SELECT * FROM posts WHERE author IN ( SELECT feed_to FROM feed WHERE feed_from = '$_SESSION[user_id]' ) ORDER BY updated DESC LIMIT $offset, $limit");

	$allauthors = $conn->query("SELECT * FROM users WHERE id IN ( SELECT feed_to FROM feed WHERE feed_from = '$_SESSION[user_id]' ) ORDER BY id DESC");
	$lastuser = $conn->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
	$max = $lastuser->fetch()['id'] - 1;
	$rand = rand(1, $max);
	$getauthors = $conn->query("SELECT * FROM users WHERE id IN ( SELECT feed_to FROM feed WHERE feed_from = '$_SESSION[user_id]' ) ORDER BY id DESC LIMIT $rand, 7");
	if (isset($_GET['all'])) {
		$authors = $allauthors;
	} else {
		$authors = $getauthors;
	}

	if ($total_rows == 0) { ?>
		
		<h1>Tu feed está vacío.</h1>
		<img class="mainimg" src="../inc/img/feed_empty.png">
		<p>Este es tu feed, aquí se mostrarán las últimas publicaciones de los usuarios que más te gusten, para añadir a un usuario a tu feed, haz click en el icono <i class="im im-frown-o"></i> en su perfil ¡y se convertirá en  una carita sonriente <i class="im im-smiley-o"></i>!</p>
		<p>Tu feed es privado, sólo tú puedes verlo y saber a quién sigues y a quién no. Así no le romperás a nadie el corazón.</p>
		<p>¿Por qué no <a href="../search.php">añades usuarios a tu feed</a>?</p>
	<?php } else { ?>
		<h3>En tu feed:</h3>
		<div class="messages">
			<?php while ($author = $authors->fetch()) { ?>
				<div class="with">
					<a href="<?php echo $author['username']; ?>">
						<img class="with" src="<?php echo $author['avatar']; ?>">
					</a>
					<a class="with username" href="<?php echo $author['username']; ?>">@<?php echo mroExcerpt($author['username'], 7) ?></a>
				</div>
			<?php } 
			if (!isset($_GET['all']) && $allauthors->rowCount() > 7) { ?>
				<div class="with more">
					<a href="<?php echo url()."/u/?feed&all"; ?>">
						<img class="with" src="<?php echo url(); ?>/u/imgs/default_user.png">
					</a>
					<a href="?feed&all" class="with username">Ver todo</a>
				</div>
			<?php } ?>
 		</div>
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
		    			<a class="<?php if($_GET['pageno'] == $pageno+1){ echo "current"; } if ($pageno+1 !== 1 && $_GET['pageno'] < $pageno+1) { echo "pagination__next"; } ?>" href="?feed&pageno=<?php echo $pageno+1; ?>"><?php echo $pageno+1 ?></a>
		    		</li>
		    	<?php $pageno++; 
		    }
		} ?>
		</ul>
	<?php }
?>
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