<title>Recuperar contraseña</title>
<section class="content">
<?php if (mroWebmail()) {
	if (isset($_GET['passwordtoken']) && strlen($_GET['passwordtoken']) > 11) {
		$token = $_GET['passwordtoken'];
		$query = $conn->query("SELECT token FROM users WHERE token = '$token'");
		if ($query->rowCount() > 11) { ?>
			<form action="" method="POST">
				<p>Reestablecer contraseña:</p>
				<input type="password" name="password" placeholder="Contraseña">
				<button type="submit" name="reset_password">Reestablecer contraseña</button>
				<p>Para crear contraseñas más seguras:</p>
				<ul>
					<li>No uses palabras reales.</li>
					<li>Usa combinaciones de letras, números y símbolos.</li>
					<li>Crea contraseñas de al menos 8 caracteres de largo.</li>
				</ul>
			</form>
		<?php } else { ?>
			<h3>Lo sentimos</h3>
			<img class="mainimg" src="../assets/img/search_empty.png">
			<p>El token no existe o ha expirado.</p>
			<p>Puedes <a href="index.php?password">solicitar un nuevo token.</a></p>
		<?php }
	} elseif (isset($_GET['sent'])) { ?>
		<h2>Muy bien...</h2>
		<img class="mainimg" src="../assets/img/phone_lock.png">
		<p>Te hemos enviado un correo con un enlace para que puedas recuperar tu contraseña.</p>
		<p>Si no ves el mensaje, por favor revisa tu carpeta de SPAM o recarga tu bandeja de entrada.</p>
	<?php } else { ?>
		<h2>Reestablecer contraseña</h2>
		<img class="mainimg" src="../assets/img/phone_lock.png">
		<p>Si no recuerdas tu contraseña puedes establecer una nueva.</p>
		<p>Usa tu usuario y email y recibirás un enlace para que puedas reestablecer tu contraseña.</p>
		<form action="" method="POST">
			<input type="text" name="username" placeholder="Usuario">
			<input type="email" name="email" placeholder="Correo">
			<button type="submit" name="request_password_token">Reestablecer contraseña</button>
		</form>
	<?php }
} else { ?>
	<h2>Lo sentimos...</h2>
	<img class="mainimg" src="../assets/img/control_panel.png">
	<p>Esta instalación no dispone de servicio de correo, por lo que no podemos enviarte un mensaje.</p>
	<p>Si no puedes recuperar tu contraseña, ponte en contacto con un administrador para recuperar tu cuenta.</p>
<?php } ?>
</section>