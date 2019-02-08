<?php 
	require_once '../config.php';
	include '../inc/php/header.php'; ?>

	<?php 
	if (isset($_GET['read'])) {
		include 'read.php';
	} elseif (uUser() && !uBlocked()) { ?>
	<section class="content">
		<?php if (isset($_GET['draft'])) {
			//borradores
			include 'draft.php';
		} elseif (isset($_GET['edit'])) {
			//editar
			include 'edit.php';
		} elseif (isset($_GET['add'])) {
			//capítulos nuevos
			include 'add.php';
		} else {
			//historias nuevas
			include 'write.php';
		} ?>
	</section>
	<?php } elseif (uUser() && uBlocked()) { 
		echo uBlocked();
	} else { ?>
		<section class="login">
			<form>
				<h3>Debes estar registrado para poder publicar.</h3>
				<img src="../inc/img/open_door.png">
				<p>Es fácil, rápido y gratis. Podrás comenzar a escribir, publicar y comentar en menos de 3 minutos.</p>
			</form>
			<?php include '../u/register.php'; ?>
		</section>
	<?php }

	include '../inc/php/footer.php';
?>