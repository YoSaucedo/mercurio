<section class="content">
<?php 
	$username = $_SESSION['username'];

	$query = $conn->query("SELECT * FROM users WHERE username = '$username'");
	$posts = $conn->query("SELECT * FROM posts WHERE author = '$_SESSION[user_id]'");
	$comments = $conn->query("SELECT * FROM comments WHERE author = '$_SESSION[user_id]'");

	while ($user = $query->fetch()) { ?>
		<title>@<?php echo $user['username']." en "; mroName(); ?></title>
		<div class="user_name">
			<h1><?php echo $user['name']; ?></h1>
		</div>
		<div class="user_banner">
			<img class="user_avatar" src="<?php echo $user['avatar']; ?>">
			<div class="user_actions">
				<a href="logout.php"><i class="im im-sign-out"></i></a>
				<a href="?edit"><i class="im im-user-settings"></i></a>
				<a href="?u=<?php echo $username; ?>"><i class="im im-user-circle"></i></a>
			</div>
			<span class="username">@<?php echo $user['username']; ?></span>
			<br/>
			<span class="userposts"><?php echo $posts->rowCount(); ?> entradas.</span>
			<span class="comments"><?php echo $comments->rowCount(); ?> comentarios.</span>
			<span class="since">desde el <?php echo date('d/m/Y', (int)$user['since']); ?></span>
		</div>
	<?php }
	include 'm/users.php'; ?>
	<h3>Tus estrellas:</h3>
	<?php include 'stars.php'; ?>
	<h3>Tus historias:</h3>
	<?php $lastposts = $conn->query("SELECT * FROM posts WHERE author = '$_SESSION[user_id]' ORDER BY updated DESC LIMIT 3");
	if ($lastposts->rowCount() < 1) { ?>
		<h1>Todavía no has escrito nada.</h1>
		<p><a href="../post">¿Por qué no publicas algo?</a></p>
	<?php } else { ?>
		<div class="posts">
		<?php while ($post = $lastposts->fetch()) {
			include '../assets/php/single_post_simple.php';
		} ?>
		</div>
		<p><a href="?u=<?php echo $_SESSION['username']; ?>">Ver todas las historias.</a></p>
	<?php } ?>
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