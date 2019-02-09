<?php 
require_once '../../config.php';
include '../../assets/php/header.php';

//usuario registrado
if (uUser()) { ?>
	<section class="content">
		<?php if (isset($_GET['with'])) {
			include 'messages.php';
		} else {
			include 'users.php';
		} ?>
	</section>
<?php //usuario desconocido
} else { ?>
	<section class="login">
		<form>
			<h3>Debes estar registrado para enviar y recibir mensajes.</h3>
			<img src="../../assets/img/phone_lock.png">
			<p>A nadie le gusta recibir cartas de desconocidos.</p>
		</form>
		<?php include '../register.php'; ?>
	</section>
<?php } include '../../assets/php/footer.php'; ?>