<?php 
$name = $_GET['section'];
$sql = $conn->query("SELECT * FROM sections WHERE slug = '$name'");
while ($section = $sql->fetch()) {
	$posts = $conn->query("SELECT * FROM posts WHERE section = '$section[id]'"); ?>
	<title>Editar <?php echo $section['name']; ?></title>
	<h2><?php echo $section['name']; ?></h2>
	<p>Actualmente hay <?php echo $posts->rowCount(); ?> historias publicadas en <?php echo $name; ?></p>
	<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
		<input type="text" name="name" placeholder="Nombre" value="<?php echo $section['name']; ?>">
		<input type="text" name="color" class="jscolor" value="<?php echo $section['color']; ?>">
		<input type="text" name="slug" placeholder="slug" value="<?php echo $section['slug']; ?>">
		<textarea name="description"><?php echo $section['description']; ?></textarea>
		<input type="checkbox" name="adminonly" <?php if ($section['adminonly'] == '1') { echo "checked"; } ?>>
		<label>Sólo administradores pueden publicar.</label>
		<a class="button" href="<?php echo url().'/?section='.$section['slug']; ?>">Ver</a>
		<button type="submit" name="section_update">Actualizar</button>
		<?php if ($section['front'] != '0' ) { ?>
			<button type="submit" name="section_unfront">Posición orgánica</button>
		<?php } else { ?>
			<button type="submit" name="section_front">Primera posición</button>
		<?php } ?>
		<h3>Borrar:</h3>
		<input type="checkbox" name="understand"><label> Deseo borrar esta sección.</label>
		<button type="submit" name="section_delete" class="delete">Borrar</button>
	</form>
<?php } ?>