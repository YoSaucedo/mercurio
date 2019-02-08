<title><?php echo mroName(); ?></title>

<h2><?php echo mroName(); ?></h2>
<p>Desde aquí puedes configurar esta instalación de Mercurio.</p>
<?php
$sql = $conn->prepare("SELECT * FROM install WHERE fingerprint = ?");
$sql->execute([mroFingerPrint()]);
while ($mro = $sql->fetch()) { ?>
	<form method="POST" action="" enctype="multipart/form-data">
		<input type="text" name="name" placeholder="Nombre del sitio" value="<?php echo $mro['name']; ?>">
		<div class="user_banner">
	        <input class="file" id="file" type="file" name="logo" accept=".jpg, .png, .gif, .webp">
	        <label class="button avatar" for="file"><i class="im im-upload"></i></label>
	        <span class="username">Subir imagen del sitio.</span>
	        <span class="username">Tamaño recomendado 320x320px.</span>
	        <span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
	    </div>
		<script type="text/javascript">
	        $('#file').change(function() {
	            $(this).next('label').append("<p>Tu archivo ha sido seleccionado.</p>");
	        })
	    </script>
	    <p>Color de cabecera:</p>
	    <input type="text" name="headerbg" class="jscolor" value="<?php mroHeaderBg(); ?>">
	    <p>Color de motivo:</p>
	    <input type="text" name="actioncolor" class="jscolor" value="<?php mroColor(); ?>">
	    <label>Este color aparecerá representando a todos los elementos que representan una acción.</label>
	    <hr/>
	    <input type="mail" name="webmail" placeholder="Webmail" value="<?php echo mroWebmail(); ?>">
	    <label>Correo del sistema.</label>
	    <p>Esta dirección de correo se usará para enviar un mensaje a los usuarios con un enlace a través del cual puedan recuperar su contraseña. Si tu servidor no dispone de servicio webmail, tus usuarios no podrán recuperar su contraseña.</p>
	    <input type="text" name="maxfilesize" placeholder="1" value="<?php echo mroMaxFileSize(); ?>">
	    <label>Máximo de MB de subida.</label>
	    <p>El tamaño máximo en MB de archivo para su subida. Algunos servidores pueden imponer sus propios límites.</p>
	    <hr/>
	    <select name="rules">
	    	<option disabled selected>Normas de la comunidad:</option>
	    	<option value="">Sin normas de la comunidad.</option>
	    	<?php $options = $conn->query("SELECT * FROM posts WHERE author IN (SELECT id FROM users WHERE role = '1')");
	    	while ($option = $options->fetch()) { ?>
	    	 	<option <?php if ($option['slug'] == mroRules()) { echo "selected"; } ?> value="<?php echo $option['slug']; ?>"><?php echo $option['title']; ?></option>
	    	<?php } ?>
	    </select>
	    <p>Puedes establecer unas normas de la comunidad que los usuarios deberán aceptar al regitrarse. Las normas de la comunidad han de ser un post de un administrador.</p>
	    <hr/>
	    <button type="submit" name="site_update">Actualizar</button>
	</form>
<?php } ?>
