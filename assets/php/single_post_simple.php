<?php $sql = $conn->query("SELECT username FROM users WHERE id = '$post[author]'");
$postauthor = $sql->fetch()['username']; ?>
<div class="single_post">
	<a href="<?php echo url()."/post/?read=".$post['slug']; ?>">
	<div class="post_card">
		<h2><?php echo $post['title']; ?></h2>
		<a class="post_author" href="<?php echo url()."/user/?u=".$postauthor ?>">@<?php echo $postauthor; ?></a>
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
	} ?>
</div>
