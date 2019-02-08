<?php $breach = array();
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off') {
		$breach['https'] = "<h3 class='adminlog bad'>HTTPS no está habilitado.</h3>
		<p class='adminlog'>Para garantizar una transferencia segura de datos HTTPS debe estar habilitado en tu servidor.</p>";
	} if (phpversion() < 5.4) { 
		$breach['phpversion'] = "<h3 class='adminlog bad'>PHP ".phpversion()."</h3>
		<p class='adminlog'><strong>Este es un error crítico. Por favor actualiza tu versión de PHP lo antes posible.</strong></p>";
	} if (ini_get("display_errors") != 0) {
		$breach['display_errors'] = "<h3 class='adminlog bad'>display_errors está habilitado.</h3>
		<p class='adminlog'>Los errores de procesado se muestran al usuario.</p>
		<p class='adminlog'>Esto puede proporcionar información sensible a un atacante, por favor desactiva esta función en tu archivo php.ini</p>
		<p class='adminlog'><code>display_errors = off</code></p>";
	} if (ini_get("expose_php") != 0) {
		$breach['expose_php'] = "<h3 class='adminlog bad'>expose_php está habilitado.</h3>
		<p class='adminlog'>La versión de PHP es mostrada en las cabeceras http.</p>
		<p class='adminlog'>Esto puede proporcionar información sensible a un atacante, por favor desactiva esta función en tu archivo php.ini</p>
		<p class='adminlog'><code>expose_php = off</code></p>";
	} if (ini_get("allow_url_include")) {
		$breach['allow_url_include'] = "<h3 class='adminlog bad'>allow_url_include está habilitado.</h3>
		<p class='adminlog'>Un atacante puede cargar código desde una url externa.</p>
		<p class='adminlog'>Esto puede proporcionar una puerta de entrada a un atacante, por favor desactiva esta función en tu archivo php.ini</p>
		<p class='adminlog'><code>allow_url_include = 0</code></p>";
	} if (ini_get("session.cookie_domain") != $_SERVER['SERVER_NAME']) {
		$breach['cookie_domain'] = "<h3 class='adminlog bad'>session.cookie_domain no está configurado.</h3>
		<p class='adminlog'>El servidor acepta cookies de otros dominios.</p>
		<p class='adminlog'>Esto puede proporcionar una puerta de entrada a un atacante, por favor configura esta función en tu archivo php.ini para que sólo acepte cookies de esta instalación.</p>
		<p class='adminlog'><code>session.cookie_domain = ".$_SERVER['SERVER_NAME']."</code></p>";
	} if (!mroWebmail()) {
		$breach['mailinfo'] = "<h3 class='adminlog concerning'>No hay webmail configurado.</h3>
		<p class='adminlog'>Tus usuarios no pueden recuperar sus contraseñas.</p>
		<p class='adminlog'>Por favor configura una dirección webmail que será usada para enviar un correo de recuperación de contraseña a tus usuarios.</p>";
	} if (empty($breach)) { ?>
		<section class="content">
			<img class="mainimg" src="../inc/img/control_panel.png">
			<p><?php mroName(); ?> funciona a máxima potencia y con los escudos al 100% gracias a Mercurio <?php mroNumber(); ?>.</p>
			<p><?php echo mroName(); ?> tiene <?php echo mroStats(); ?></p>
			<p>Además hay <?php echo substr(folder_size('../post/cover')/1048576, 0, 5); ?>MB en portadas y <?php echo substr(folder_size('../u/imgs')/1048576, 0, 5); ?>MB en avatares de usuario.</p>
			<p><a href="?config">Configuración.</a></p>
		</section>
	<?php } elseif (isset($_GET['breaches'])) {
		echo "<strong>Si no sabes como solucionar estos problemas, por favor, ponte en contacto con el servicio de asistencia de tu alojamiento.</strong>";
		foreach ($breach as $message) {
			echo $message;
		}
	} else { ?>
		<section class="content">
			<img class="mainimg" src="../inc/img/broken_panel.png">
			<h3 style="color: red;">Hay <?php echo count($breach); ?> brechas de seguridad.</h3>
			<p><?php mroName(); ?> no es seguro, por favor soluciona los <a href="?breaches">siguientes problemas</a>.</p>
			<?php if (preg_match('/localhost/', curPageURL())) { ?>
				<p>Si estás en un entorno de pruebas puedes ignorar estos errores.</p>
			<?php } ?>
			<p><?php echo mroName(); ?> tiene <?php echo mroStats(); ?></p>
			<p>Además hay <?php echo substr(folder_size('../post/cover')/1048576, 0, 5); ?>MB en portadas y <?php echo substr(folder_size('../u/imgs')/1048576, 0, 5); ?>MB en avatares de usuario.</p>
			<p><a href="?config">Configuración del sitio.</a></p>
		</section>
	<?php } ?>