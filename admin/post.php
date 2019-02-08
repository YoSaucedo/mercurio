<?php 
$id = $_GET['post'];
$sql = $conn->query("SELECT * FROM posts WHERE id = '$id'");
while ($post = $sql->fetch()) { ?>
	<title>Editar <?php echo $post['title']; ?></title>
	<h2><?php echo $post['title']; ?></h2>
	<p>Publicado el <?php echo date('d/m/Y @ h:i:s', (int)$post['stamp']); ?></p>
	<p>Última actualización: <?php echo date('d/m/Y @ h:i:s', (int)$post['updated']); if ($post['updated_m'] == 'changes') { echo ' (Cambio en el texto)'; } elseif ($post['updated_m'] == 'posted') { echo ' (Recién publicado)'; } elseif ($post['updated_m'] == 'nochange') { echo ' (actualización sin cambio en el texto)'; } else { echo ' (capítulo)'; }?></p>
	<p><?php echo $post['prints']; ?> impresiones.</p>
	<form method="POST" action="">
		<a class="button" href="<?php echo url()."/post/?read=".$post['slug']; ?>">Ver</a>
		<input type="hidden" name="post_section" value="<?php echo $post['section']; ?>">
		<?php if ($post['front'] != '0') { ?>
			<button type="submit" name="post_unfront">Retirar de la portada</button>
		<?php } else { ?>
			<button type="submit" name="post_front">Enviar a la portada</button>
		<?php } ?>
		<h3>Borrar:</h3>
		<input type="checkbox" name="understand"><label> Deseo borrar esta historia.</label>
		<button type="submit" name="section_delete" class="delete">Borrar</button>
	</form>
<?php } ?>