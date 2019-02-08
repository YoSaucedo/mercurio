<section class="content">
<?php 
	$query = $conn->prepare("SELECT * FROM posts WHERE slug = ?");
	$query->execute([$_GET['read']]);

	while ($post = $query->fetch()) { ?>
		<title><?php echo $post['title']; ?></title>
		<section class="post_read">
			<div class="post_title">
				<h1><?php echo $post['title']; ?></h1>
				<div class="post_actions">
					<?php if (uUser() && $_SESSION['user_id'] == $post['author']) { 
						if (isset($_GET['and'])) { ?>
							<a title="Editar" href="../post/?edit=<?php echo $post['id']; ?>&and=<?php echo $_GET['and']; ?>"><i class="im im-pencil"></i></a>
						<?php } else { ?>
							<a title="Editar" href="../post/?edit=<?php echo $post['id']; ?>"><i class="im im-pencil"></i></a>
						<?php }?>
						<a title="Añadir capítulo" href="../post/?add=<?php echo $post['id']; ?>"><i class="im im-plus"></i></a>
					<?php } if (uAdmin()) { ?>
						<a title="Administar" href="../admin/?post=<?php echo $post['id']; ?>"><i class="im im-edit"></i></a>
					<?php } ?>
				</div>
			</div>
			<?php if (!empty($post['cover'])) { ?>
				<div class="post_cover" style="background-image: url(<?php echo $post['cover']; ?>);"></div>
			<?php } ?>
			<div class="post_author">
				<?php //avatar e información del autor 
				$author = $conn->prepare("SELECT * FROM users WHERE id = ?"); 
				$author->execute([$post['author']]);
				while ($user = $author->fetch()) { ?>
					<a href="../u/?u=<?php echo $user['username']; ?>"><img class="post_author" src="../u/<?php echo $user['avatar']; ?>"></a>
					<span>por <a href="../u/?u=<?php echo $user['username']; ?>"><?php echo $user['name']; ?></a></span>
				<?php }
				//capítulos
				$subs = $conn->prepare("SELECT * FROM subposts WHERE post = ? ORDER BY id ASC");
				$subs->execute([$post['id']]);
				if ($subs->rowCount() != 0) {
				 	$hassubs = true; ?>
					<span><?php echo $subs->rowCount()+1; ?> <a href="?read=<?php echo $post['slug']; ?>&chapters">capítulos</a></span>
				<?php } 
				if (isset($_GET['and'])) {
				 	$sub = $conn->query("SELECT * FROM subposts WHERE id = '$_GET[and]'");
					$read = $sub->fetch()['body'];
				 } else {
				 	$read = $post['body'];
				 } ?>
				<span>el <?php echo date('d/m/Y', (int)$post['stamp']); ?></span>
				<?php $getsection = $conn->query("SELECT * FROM sections WHERE id = '$post[section]'"); 
				while ($section = $getsection->fetch()) { ?>
				 	<span>en <a href="../?section=<?php echo $section['slug']; ?>"><?php echo $section['name']; ?></a></span>
				<?php } ?>
				<span title="Tiempo de lectura aprox.">&asymp; <?php $words = str_word_count(strip_tags($read)); echo ceil($words/190)." minutos"; ?></span>
			</div>
			<div class="post_text">
				<?php if (isset($_GET['chapters'])) { ?>
					<ol>
						<li><a href="?read=<?php echo $post['slug']; ?>"><?php echo $post['title']; ?></a></li>
					<?php while ($sub = $subs->fetch()) { ?>
						<li><a class="chapter" href="?read=<?php echo $post['slug']; ?>&and=<?php echo $sub['id'] ?>"><?php echo $sub['title']; ?></a></li>
					<?php } ?>
					</ol>
				<?php } else {
					echo htmlspecialchars_decode($read);
 				} ?>
			</div>
			<?php //navegación capítulos
			if (isset($hassubs)) { ?>
				<div class="next">
					<?php
					if (isset($_GET['and'])) {
						$sub = $conn->query("SELECT * FROM subposts WHERE post = '$post[id]' AND id > '$_GET[and]'");
					} else {
						$sub = $conn->query("SELECT * FROM subposts WHERE post = '$post[id]'");
					}
					$i = 0;
					while ($i < 1 && $next = $sub->fetch()) { ?>
						<a href="?read=<?php echo $_GET['read']."&and=".$next['id']; ?>">
							<i class="im im-angle-right"></i>
							<?php if (time() - $next['stamp'] < 86400) { ?>
							<i title="Nuevo capítulo" class="im im-bell-active"></i>
							<?php } echo $next['title']; $i++; ?>
						</a>
					<?php } ?>
					<div class="chapters nav bg" style="background-image: url(<?php echo $post['cover']; ?>);"></div>
				</div>
			<?php } ?>
			<div class="post_social" id="stars">
				<?php //estrellas
				$stars = $conn->prepare("SELECT * FROM stars WHERE post = ?");
				$stars->execute([$post['id']]); 
				//posts relacionados
				$related = $conn->prepare("SELECT *, MATCH(title, body) AGAINST(?) AS score FROM posts WHERE MATCH(title, body) AGAINST(?) AND id != ? ORDER BY score DESC LIMIT 3");
				$related->execute([$post['title'], $post['title'], $post['id']]); ?>
				<p class="stars">
					<?php if (uUser() && $stars->fetch()['star_from'] == $_SESSION['username']) { ?>
						<a href="?read=<?php echo $post['slug']; ?>&quitstar"><i class="im im-star star_from"></i></a>
					<?php } else { ?>
						<a href="?read=<?php echo $post['slug']; ?>&addstar"><i class="im im-star"></i></a>
					<?php } ?>
					 A <?php echo $stars->rowCount(); if ($stars->rowCount() == 1) { echo " usuario le ha gustado."; } else { echo " usuarios les ha gustado."; } ?>	
				</p>
				<div class="post_recomm">
				<?php while ($recomm = $related->fetch()) { ?>
					<div class="single_post">
					<a href="?read=<?php echo $recomm['slug']; ?>">
					<?php if (!empty($recomm['cover'])) { ?>
						<div class="post_cover" style="background-image: url(<?php echo $recomm['cover']; ?>);"></div>
					<?php } else { ?>
						<div class="post_cover" style="background-image: url('cover/default.png');"></div>
					<?php } ?>
					<h3><?php echo $recomm['title']; ?></h3>
					</a>
					</div>
				<?php } ?>
				</div>
			</div>
		</section>
		<hr id="comments">
		<?php
		//conteo de visitas
		if (uUser() && $_SESSION['user_id'] != $post['author']) {
			if (isset($_GET['and'])) {
				$sub = $conn->query("SELECT prints FROM subposts WHERE id = '$_GET[and]'");
				$prints = $sub->fetch()['prints']+1;
				$sql = $conn->prepare("UPDATE subposts SET prints = ? WHERE id = ?");
				$sql->execute([$prints, $_GET['and']]);
				//popularidad (+1)
					$post = $conn->prepare("SELECT post FROM subposts WHERE id =?");
					$post->execute([$_GET['and']]);
					$postid = $post->fetch()['post'];
					$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$postid'");
					$i = $getpop->fetch()['pop'] + 1;
					$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$postid'");
			} else {
				$prints = $post['prints']+1;
				$sql = $conn->prepare("UPDATE posts SET prints = ? WHERE id = ?");
				$sql->execute([$prints, $post['id']]);
				//popularidad (+1)
					$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$post[id]'");
					$i = $getpop->fetch()['pop'] + 1;
					$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$post[id]'");
			}
			//conteo del usuario
			if (uUser()) {
				$sql = $conn->prepare("SELECT prints FROM users WHERE username = ?");
				$sql->execute([$_SESSION['username']]);
				$uprints = $sql->fetch()['prints']+1;
				$print = $conn->prepare("UPDATE users SET prints = ? WHERE username = ?");
				$print->execute([
					$uprints,
					$_SESSION['username']
				]);
			}
		}
		include 'comments.php';
	}
?>
</section>
<script src="../inc/js/masonry.pkgd.min.js"></script>
<script src="../inc/js/infinite-scroll.pkgd.min.js"></script>
<script type="text/javascript">
	$('.post_recomm').masonry({
		// options
		itemSelector: '.single_post',
		columnWidth: 260
	});
</script>