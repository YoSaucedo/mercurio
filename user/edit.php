<title>Editar configuración de perfil.</title>
<section class="content">
<?php
	$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
	$query->execute([$_SESSION['username']]);

	while ($result = $query->fetch()) { ?>
		<form class="user" method="POST" action="" enctype="multipart/form-data">
			<h2>Personaliza tu perfil, <?php echo $_SESSION['username']; ?>.</h2>
			<input type="text" name="name" placeholder="Nombre público" value="<?php echo $result['name']; ?>">
			<div class="user_banner">
				<input class="file" id="file" type="file" name="avatar" accept=".jpg, .png, .gif, .webp">
				<label class="button avatar" for="file" style="background-image: url(<?php echo url()."/user/".$result['avatar']; ?>);"><i class="im im-upload"></i></label>
				<span class="username">Tamaño recomendado 200x200px.</span>
				<span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
				<span class="username">Si tu avatar no se actualiza recarga tu caché.</span>
			</div>
			<script type="text/javascript">
                $('#file').change(function() {
                    $(this).next('label').append("<p>Tu archivo ha sido seleccionado.</p>");
                })
            </script>
			<textarea oninput="textCounter(this,'counter',255);" name="bio" placeholder="Escribe una pequeña biografía acerca de ti."><?php echo $result['bio']; ?></textarea>
			<input disabled maxlength="3" size="3" value="255" id="counter">
			<script>
			function textCounter(field,field2,maxlimit){
				var countfield = document.getElementById(field2);
				if ( field.value.length > maxlimit ) {
					field.value = field.value.substring( 0, maxlimit );
					return false;
				} else {
					countfield.value = maxlimit - field.value.length;
				}
			}
			</script>
			<button type="submit" name="profile_update">Actualizar</button>
			<a class="button" href="?u=<?php echo $result['username']; ?>">Ver perfil</a>
			<hr/>
			<h3>Configuración</h3>
			<h4>Modo oscuro:</h4>
			<?php if (uDarkMode()) { ?>
				<button type="submit" name="darkmodeoff"><i class="im im-eye-off"></i> Deshabilitar</button>
			<?php } else { ?>
				<button type="submit" name="darkmodeon"><i class="im im-eye"></i> Habilitar</button>
			<?php } ?>
			<p>Cuidado, esta opción puede afectar la visualización de algunos elementos.</p>
			<h4>Cambiar correo:</h4>
			<input type="email" name="email" value="<?php echo $result['email']; ?>" placeholder="Correo">
			<button type="submit" name="email_update">Actualizar</button>
			<p>Tu correo es privado y solo se usará para que puedas recuperar tu contraseña.</p>
			<h4>Cambiar contraseña:</h4>
			<input type="password" name="password" placeholder="Contraseña">
			<button type="submit" name="password_update">Actualizar</button>
			<hr/>
			<h4>Borrar mi cuenta</h4>
			<p>Puedes borrar tu cuenta, pero esto también borrará todos tus datos en el sitio.</p>
			<input type="checkbox" name="understand">
			<label for="understand">Entiendo que borrar mi cuenta también borrará toda mi información.</label>
			<p>Te echaremos de menos.</p>
			<button type="submit" name="delete_account">Deseo borrar mi cuenta</button>
		</form>
	<?php }
?>
</section>
