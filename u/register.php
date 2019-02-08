<form class="register" method="POST" action="">
	<p>Si no tienes cuenta puedes registrate para comenzar a leer y escribir.</p>
	<input type="text" name="username" <?php if (isset($_GET['u'])) { echo "value='".$_GET['u']."'"; } ?> placeholder="Usuario">
	<input type="email" name="email" placeholder="Correo">
	<input type="password" name="password" placeholder="Contraseña">
	<?php if (mroRules()) { ?>
		<label><input type="checkbox" name="rules">He leído y acepto las <a href="<?php echo url()."/post/?read=".mroRules(); ?>">normas de la comunidad</a>.</label>
	<?php } ?>
	<button type="submit" name="register">Empezar</button>
</form>