<?php $sql = $conn->query("SELECT username FROM users WHERE id = '$post[author]'");
$postauthor = $sql->fetch()['username']; ?>
<div class="single_post">
	<a href="<?php echo url()."/post/?read=".$post['slug']; ?>">
	<?php if (!empty($post['cover'])) { ?>
		<div class="post_cover" style="background-image: url(<?php echo url()."/post/".$post['cover']; ?>);"></div>
	<?php } ?>
	<div class="post_card">
		<h2><?php echo $post['title']; ?></h2>
		<a class="post_author" href="<?php echo url()."/u/?u=".$postauthor ?>">@<?php echo $postauthor; ?></a>
		<p><?php if (preg_match('/&lt;iframe/', $post['body'])) { echo "Esta historia contiene un texto incrustado.";
		} else { echo mroExcerpt($post['body'], 333); } ?></p>
	</div>
	</a>
	<?php 
		//nuevo capítulo
		if (!empty($post['updated']) && (time() - $post['updated']) < 86400 /*segundos por día*/ ) { 
			if ($post['updated_m'] == "posted") { ?>
				<p class="post_chapters"><a href="<?php echo url()."/post/?read=".$post['slug']; ?>"><i class="im im-idea"></i> Nueva historia.</a></p>
			<?php } if ($post['updated_m'] == "subpost") { ?>
				<p class="post_chapters"><a href="<?php echo url()."/post/?read=".$post['slug']."&chapters"; ?>"><i class="im im-book"></i> Nuevo capítulo.</a></p>
			<?php } if ($post['updated_m'] == "changes") { ?>
				<p class="post_chapters"><a href="<?php echo url()."/post/?read=".$post['slug']; ?>"><i class="im im-bell-active"></i> Contenido reciente.</a></p>
			<?php }
		}
		//comentarios
		$comments = $conn->prepare("SELECT COUNT(*) FROM comments WHERE post = ?");
		$comments->execute([$post['id']]);
		//estrellas
		$stars = $conn->prepare("SELECT * FROM stars WHERE post = ?");
		$stars->execute([$post['slug']]); ?>
	<p class="comments"><a href="<?php echo url()."/post/?read=".$post['slug']."#comments"; ?>"><i class="im im-speech-bubble"></i> <?php echo $comments->fetchColumn(); ?> comentarios.</a></p>
	<p class="stars"><a href="<?php echo url()."/post/?read=".$post['slug']."#stars"; ?>"><i class="im im-star <?php if (uUser() && $stars->fetch()['star_from'] == $_SESSION['username']) { echo "star_from"; } ?>"></i> <?php echo $stars->rowCount(); ?> estrellas.</a></p>
</div>