<?php
$username = $_GET['user'];
$sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
while ($user = $sql->fetch()) { ?>
	<title>Administrar @<?php echo $user['username']; ?></title>
	<h2>@<?php echo $user['username']; ?></h2>
	<?php if ($user['role'] == 'admin' && $user['since'] < $_SESSION['since']) { ?>
		<h3>Este usuario es otro administrador.</h3>
		<p>Y lleva más tiempo siéndolo que tú, por lo que no puedes hacer cambios en su cuenta.</p>
		<p>Moraleja: métete con alguien más chico.</p>
	<?php } else { ?>
		<form method="POST" action="">
			<h3>Corazones:</h3>
			<p>Puedes otorgarle corazones a un usuario para recompensarlo por su actividad o cualquier motivo que consideres.</p>
			<input type="text" name="attr" placeholder="¿Por qué?">
			<input type="text" name="color" class="jscolor">
			<button type="submit" name="badge_user">Otorgar</button>
			<?php $sql = $conn->query("SELECT * FROM badges WHERE receiver = '$username'");
			while ($badge = $sql->fetch()) { ?>
				<input type="hidden" name="badge_id" value="<?php echo $badge['id'] ?>">
				<button class="badge delete" type="submit" name="unbadge" title="Eliminar <?php echo $badge['attr']; ?>"><i style="color: #<?php echo $badge['color']; ?>" class="im im-heart"></i></button>
			<?php } ?>
			<hr/>
			<?php if ($user['role'] == '1') { ?>
				<h3>Promover:</h3>
				<p>Este usuario también es un administrador, pero puedes revocarle su rol.</p>
				<button type="submit" name="unadmin_user">Revocar administración</button>
			<?php } else { ?> 
				<h3>Promover:</h3>
				<p>Puedes hacer que este usuario sea administrador. Tendrá acceso a todo el panel de administración, pero no podrá hacer cambios en otros administradores que se hayan registrado antes que él.</p>
				<a class="button" href="<?php echo url().'/u/?u='.$user['username']; ?>">Ver perfil</a>
				<button type="submit" name="admin_user">Hacer administrador</button>
			<?php }	?>
			<h3>Bloquear:</h3>
			<?php if ($user['access'] != '1') { 
				if ((time()-$user['access']) > 259200) { ?>
				 	<p>Este usuario lleva bloqueado desde el <?php echo date('d/m/Y', (int)$user['access']); ?>. Quizás vaya siendo hora de desbloquearlo.</p>
				<?php } ?>
				<p>Este usuario está bloqueado.</p>
				<button type="submit" name="unblock_user">Desbloquear</button>
			<?php } else { ?> 
				<p>Puedes bloquear a este usuario, esto le impedirá participar publicando nuevas historias y comentarios, o editando lo que ya tenía publicado.</p>
				<button type="submit" name="block_user">Bloquear</button>
			<?php } ?>
			<h3>Borrar:</h3>
			<input type="checkbox" name="understand">
			<label> Entiendo que borrar a este usuario es una acción irrevocable. Tengo motivos para hacerlo.</label>
			<button type="submit" name="delete_user" class="delete">Borrar</button>
		</form>
	<?php } ?>
<?php } ?>