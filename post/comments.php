<section class="comments">
<h3>Comentarios.</h3>
<?php if (uUser()) { 
	if (uBlocked()) {
		echo uBlocked();
	} else { ?>
		<div class="parent">
		<form class="child comment" action="" method="post">
			<?php //avatar e información del autor 
				$author = $conn->prepare("SELECT username FROM users WHERE id = ?"); 
				$author->execute([$post['author']]);
			?>
			<input type="hidden" name="post_author" value="<?php echo $author->fetch()['username']; ?>">
			<input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
			<textarea id="fixer" name="comment_body" placeholder="Escribe aquí tu comentario."></textarea>
			<script type="text/javascript">
				function toggleFixed() {
				   adjustWidth();
				   $(".child").toggleClass("fixed");
				 }
				 function adjustWidth() {
				   var parentwidth = $(".parent").width();
				   $(".child").width(parentwidth);
				 }
				 $(function() {
				   $("#fixer").click(
				     function() {
				       toggleFixed();
				    });
				   $(window).resize(
				     function() {
				       adjustWidth();
				    })
				 });
			</script>
			<button type="submit" name="comment">Enviar comentario</button>
		</form>
		</div>
	<?php }
} else { ?>
	<h3>Debes estar registrado para poder comentar.</h3>
	</section>
	<section class="login">
		<?php include '../u/register.php';
		include '../u/login.php'; ?>
	</section>
	<section class="comments">
<?php } ?>
<ul class="comments">
<?php
	$id = $post['id'];
	$query = $conn->query("SELECT * FROM comments WHERE post = '$id'");
	while ($comment = $query->fetch()) { ?>
	<li id="comment<?php echo $comment['id']; ?>"><?php
		//avatar
		$author = $comment['author'];
		$getavatar = $conn->query("SELECT * FROM users WHERE id = '$author'");
		while ($user = $getavatar->fetch()) { ?>
			<a class="avatar" href="../u/?u=<?php echo $user['username']; ?>">
				<img src="../u/<?php echo $user['avatar']; ?>">
			</a>
	<div>
		<a class="author" href="../u/?u=<?php echo $user['username']; ?>">@<?php echo $user['username']; ?></a>
		<?php } ?>
		<time datetime="<?php echo date('d/m/Y', (int)$comment['stamp']); ?>"><?php echo date('d/m/Y', (int)$comment['stamp']); ?></time>
		<?php if (uUser()) { ?>
			<form class="commented" method="post" action="">
				<input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
				<input type="hidden" name="comment_author" value="<?php echo $comment['author']; ?>">
				<?php if (uAdmin() || $_SESSION['user_id'] == $post['author'] || $_SESSION['user_id'] == $comment['author']) { ?>
					<button title="Borrar" type="submit" name="delete_comment"><i title="Borrar" class="im im-data-delete"></i></button>
				<?php } ?>
				<a href="#fixer" id="ping<?php echo $comment['id']; ?>"><i title="Responder" class="im im-speech-bubble-comment"></i></a>
				<script type="text/javascript">
					$(document).on('mouseup', '#ping<?php echo $comment['id']; ?>', function() {
						$(this).hide();
						$(".child").toggleClass("fixed");
						var parentwidth = $(".parent").width();
						$(".child").width(parentwidth);
						$('#fixer').val('@<?php echo $comment['name']; ?>#<?php echo $comment['id']; ?> ').show().focus();
						setTimeout(function() {
							$('#ping<?php echo $comment['id']; ?>').show();
						}, 100);
					});
				</script>
			</form>
		<?php } ?>
		<p><?php echo nl2br($comment['body']); ?></p>
	</div>
	</li>
<?php } ?>
</ul>
</section>