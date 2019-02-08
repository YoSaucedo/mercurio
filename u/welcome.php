<?php 
	require_once '../config.php';
	include '../inc/php/header.php';

	if (uUser()) {
	$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
	$query->execute([$_SESSION['username']]);

	while ($result = $query->fetch()) { ?>
		<title>Bienvenido a <?php mroName(); ?></title>
		<section class="content">
			<form class="welcome" method="POST" action="" enctype="multipart/form-data">
			<h2>Hola, <?php echo $_SESSION['username']; ?>.</h2>
			<input type="text" name="name" placeholder="Nombre público" value="<?php echo $result['name']; ?>">
			<label>Escoge un nombre público.</label>
			<p>Tu nombre público es distinto de tu nombre de usuario. Tu nombre de usuario no se puede cambiar, pero puedes cambiar tu nombre público siempre que quieras.</p>
			<div class="user_banner">
				<input class="file" id="file" type="file" name="avatar" accept=".jpg, .png, .gif, .webp">
				<label class="button avatar" for="file" style="background-image: url(<?php echo url()."/u/".$result['avatar']; ?>);"><i class="im im-upload"></i></label>
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
			<button type="submit" name="profile_update">Siguiente</button>
			<a href="<?php echo url(); ?>">Omitir</a>
			</form>
		</section>
	<?php }
	} else {
		header('location: ../');
	}

	include '../inc/php/footer.php';
?>