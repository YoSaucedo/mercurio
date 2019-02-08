<?php
	if (isset($_POST['mroInstall'])) {
		if (!empty($_POST['mroName'])) {
			$mroName = $_POST['mroName'];
			if (!empty($_POST['mroWebmail'])) {
				$mroWebmail = $_POST['mroWebmail'];
			} else {
				$mroWebmail = false;
			}
			if (!empty($_POST['mroMaxFileSize'])) {
				$mroMaxFileSize = $_POST['mroMaxFileSize'];
			} else {
				$mroMaxFileSize = "1";
			}
			$fingerprint = substr(md5($mroName), 0, 11);
			$sql = $conn->prepare("INSERT INTO install (fingerprint, name, webmail, maxfilesize) VALUES (?, ?, ?, ?)");
			if ($sql->execute([$fingerprint, $mroName, $mroWebmail, $mroMaxFileSize])){
				$config = getcwd()."/config.php";
				$line = 29;
				$lines = file( $config , FILE_IGNORE_NEW_LINES );
				$lines[$line] = "    function mroFingerPrint(){ return '$fingerprint'; }\n?>";
				if (file_put_contents( $config , implode( "\n", $lines ) )) {
					unlink('install.php');
					header('location: u');
				}
			}
		} else {
			echo "<p class ='error'>No puedes dejar el nombre vacío.</p>";
		}
	}
?>
<head>
	<title>Mercurio</title>
	<meta charset="utf-8" type="text/content">
	<link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/inc/css/styles.css">
</head>
<form method="POST" action="">
	<h1>Bienvenido a Mercurio.</h1>
	<p>Enhorabuena, tu instalación está lista para funcionar. Puedes encontrar información mas detallada sobre este punto y otros aspectos de Mercurio en el archivo readme.</p>
	<input type="text" name="mroName" placeholder="<?php mroName(); ?>">
	<label>Nombre de tu instalación</label>
	<hr/>
	<input type="text" name="mroWebmail" placeholder="correo@mercurio">
	<label>Correo del sistema</label>
	<p>Este correo lo usará el sistema para enviar a los usuarios un enlace en el que puedan recuperar su contraseña en caso de pérdida. Ha de ser una cuenta de correo en el mismo servidor de la instalación.</p>
	<p>Si no tienes una dirección de correo tus usuarios no podrán recuperar sus contraseñas.</p>
	<input type="text" name="mroMaxFileSize" placeholder="1MB">
	<label>Peso máximo en MB</label>
	<p>Peso máximo en MB para la subida de archivos.</p>
	<p>Algunos servidores pueden imponer sus propios límites de tamaño.</p>
	<hr/>
	<p>Ahora debes registrarte como usuario para acceder al panel de administrador y terminar de configurar tu sitio.</p>
	<button type="submit" name="mroInstall">Siguiente</button>
</form>