<section class="content">
<?php
	$author = $_GET['u'];
	$query = $conn->query("SELECT * FROM users WHERE username = '$author'");
	while ($author = $query->fetch()) {
	$posts = $conn->query("SELECT * FROM posts WHERE author = '$author[id]' ORDER BY id DESC");
	$comments = $conn->query("SELECT id FROM comments WHERE author = '$author[id]'"); ?>
		<title>@<?php echo $_GET['u']; ?></title>
		<div class="user_name">
			<h1><?php echo $author['name']; ?></h1>
			<div class="user_badges"><?php uBadges(); ?></div>
		</div>
		<div class="user_banner">
			<img class="user_avatar" src="<?php echo $author['avatar']; ?>">
			<div class="user_actions">
				<?php if (uUser() && $_SESSION['username'] != $author['username']) {
					$sql = $conn->prepare("SELECT * FROM feed WHERE feed_from = ? AND feed_to = ?");
					$sql->execute([$_SESSION['user_id'], $author['id']]);
					if ($sql->rowCount() == 0) { ?>
						<a title='Añadir al feed' href='?u=<?php echo $author['username']; ?>&addfeed'><i class='im im-frown-o'></i></a>
					<?php } else { ?>
						<a title='Quitar del feed' href='?u=<?php echo $author['username']; ?>&quitfeed'><i class='im im-smiley-o'></i></a>
					<?php } ?>
					<a class="fixer"><i class="im im-paperplane"></i></a>
				<?php if (uAdmin()) { ?>
					<a title='Administrar usuario' href='../admin/?user=<?php echo $author['username']; ?>'><i class='im im-edit'></i></a>
				<?php } 
				} ?>
			</div>
			<span class="username">@<?php echo $author['username']; ?></span>
			<br/>
			<span class="user_posts"><?php echo $posts->rowCount(); ?> entradas.</span>
			<span class="user_comments"><?php echo $comments->rowCount(); ?> comentarios.</span>
			<span class="since">desde el <?php echo date('d/m/Y', (int)$author['since']); ?></span>
		</div>
		<p class="user_bio"><?php echo $author['bio']; ?></p>
	<?php } 
	if (isset($_SESSION['username']) && $_SESSION['username'] != $author['username']) { include 'm/send.php'; } ?>
	<div class="posts">
	<?php while ($post = $posts->fetch()) {
		include '../assets/php/single_post.php';
	} ?>
	</div>
</section>
<script src="../assets/js/masonry.pkgd.min.js"></script>
<script src="../assets/js/infinite-scroll.pkgd.min.js"></script>
<script type="text/javascript">
	$('.posts').masonry({
		// options
		itemSelector: '.single_post',
		columnWidth: 260
	});
</script>
	<?php //ese usuario no existe
	if ($query->rowCount() == 0) { ?>
		<section class="login">
			<form>
				<h3>No tenemos a ningún usuario llamado <?php echo $_GET['u']; ?></h3>
				<img src="../assets/img/404.png">
				<p>¡Pero eso es bueno! Tú o alguien que conozcas puede usar este nombre.</p>
			</form>
			<?php include 'register.php'; ?>
		</section>
	<?php } ?>