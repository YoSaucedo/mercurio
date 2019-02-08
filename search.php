<?php
	require_once 'config.php';
	$lastpost = $conn->query("SELECT id FROM posts ORDER BY id DESC LIMIT 1");
	$max = $lastpost->fetch()['id'] - 1;
	$rand = rand(1, $max);
	if (!isset($_POST['search'])) {
		$posts = $conn->query("SELECT * FROM posts ORDER BY id LIMIT $rand, 4");
		$randomword = mb_strtolower($posts->fetch()['title']);
	}
	if (isset($_POST['search']) && strlen($_POST['searchfor']) > 3) {
		$for = $_POST['searchfor'];
		header('location: ?for='.$for);
	}
	if (isset($_GET['for'])) {
		$search = $_GET['for'];
		if ($search == ":prints") {
			$randomword = "Lo más visto.";
			$users = $conn->query("SELECT * FROM users LIMIT 0");
			$posts = $conn->query("SELECT * FROM posts ORDER BY prints DESC LIMIT 24");
		} elseif ($search == ":reads") {
			$randomword = "Los que más leen.";
			$users = $conn->query("SELECT * FROM users ORDER BY prints DESC LIMIT 24");
			$posts = $conn->query("SELECT * FROM posts LIMIT 0");
		} elseif ($search == ":admin") {
			$randomword = "Estos usuarios son administradores:";
			$users = $conn->query("SELECT * FROM users WHERE role = '1' ORDER BY id ASC");
			$posts = $conn->query("SELECT * FROM posts LIMIT 0");
		} elseif (preg_match('/@/', $search)) {
			$user = preg_replace('/@/', '', $search);
			$users = $conn->query("SELECT * FROM users WHERE username LIKE '%$user%' OR name LIKE '%$user'");
			if (isset($_GET['pageno'])) {
				$pageno = $_GET['pageno'];
			} else {
				$pageno = 1;
			}
			$limit = 12;
			$offset = ($pageno-1) * $limit;
			$getposts = $conn->query("SELECT * FROM posts WHERE author LIKE '%$search%'");
			$total_rows = $getposts->rowCount();
			$total_pages = ceil($total_rows/$limit);
			$posts = $conn->query("SELECT * FROM posts WHERE author LIKE '%$search%' LIMIT $offset, $limit");
		} else {
			$users = $conn->query("SELECT * FROM users WHERE username LIKE '%$search%' OR name LIKE '%$search%'");
			if (isset($_GET['pageno'])) {
				$pageno = $_GET['pageno'];
			} else {
				$pageno = 1;
			}
			$limit = 12;
			$offset = ($pageno-1) * $limit;
			$getposts = $conn->query("SELECT *, MATCH(title, body) AGAINST('$search') AS score FROM posts WHERE MATCH(title, body) AGAINST('$search')");
			$total_rows = $getposts->rowCount();
			$total_pages = ceil($total_rows/$limit);
			$posts = $conn->query("SELECT *, MATCH(title, body) AGAINST('$search') AS score FROM posts WHERE MATCH(title, body) AGAINST('$search') ORDER BY score DESC LIMIT $offset, $limit");
		}
	}
	include 'inc/php/header.php';  ?>
	<title>Buscar en <?php echo mroName(); ?></title>
	<section class="content">
	<form class="search" method="POST" action="">
		<button type="submit" name="search"></button>
		<input autofocus type="text" name="searchfor" placeholder="<?php if (isset($search)) { echo $posts->rowCount()+$users->rowCount()." resultados"; } else { echo $randomword; }?>" value="<?php if (isset($search)) { echo $search; } ?>">
		<i class="im im-magnifier"></i>
	</form>
	<?php
		if (isset($_GET['for']) && $users->rowCount() == 0 && $posts->rowCount() == 0) { ?>
			<h3>No hemos encontrado nada.</h3>
			<img class="mainimg" src="inc/img/search_empty.png">
			<p>No hubo ninguna coincidencia para <strong><?php echo $search; ?></strong>.</p>
			<p>Nuestro buscador es muy <s>malo</s> sensible, por favor intenta estos consejos:</p>
			<ul>
				<li>Comprueba que has escrito bien lo que estás buscando.</li>
				<li>Intenta repetir la búsqueda con otras palabras distintas.</li>
				<li>Puedes intentar una búsqueda en google usando <br/><i>site:<?php echo url()." ".$search;?></i></li>
			</ul>
			<p>Disculpa las molestias. Si no encuentras nada, tal vez una canción te anime.</p>
	<?php } elseif (isset($_GET['for'])) {
		if (isset($users) && $users->rowCount() != 0) { ?>
			<div class="users">
				<?php while ($user = $users->fetch()) { include 'inc/php/usercard.php'; } ?>
			</div>
		<?php } ?>
		<div class="posts">
			<?php while ($post = $posts->fetch()) { include 'inc/php/single_post.php'; } ?>
		</div>
		<?php
	}
	if (isset($total_pages) && $total_pages > 1) { ?>
		<div class="page-load-status">
			<p class="infinite-scroll-request"><i class="im im-spinner" title="Cargando"></i></p>
			<p class="infinite-scroll-last"><i class="im im-archive" title="¡Has llegado al final!"></i></p>
			<p class="infinite-scroll-error"><i class="im im-cloud" title="¿Qué has hecho?"></i></p>
		</div>
		<ul class="hidden pagination">
		<?php $pageno = 0;
	    while ($pageno < $total_pages) { ?>
	    		<li>
	    			<a class="<?php if (isset($_GET['pageno']) && $pageno+1 == $_GET['pageno']) { echo "current"; } if($pageno+1 !== 1 && $_GET['pageno'] < $pageno+1) { echo "pagination__next"; } ?>" href="search.php?for=<?php echo $_GET['for']; ?>&pageno=<?php echo $pageno+1; ?>"><?php echo $pageno+1 ?></a>
	    		</li>
	    	<?php $pageno++; 
	    } ?>
	    </ul>
	<?php } ?>
	</section>
	<script src="inc/js/masonry.pkgd.min.js"></script>
	<script src="inc/js/infinite-scroll.pkgd.min.js"></script>
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
<?php include 'inc/php/footer.php'; ?>