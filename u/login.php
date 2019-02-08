<title>Acceder a <?php mroName(); ?></title>
<form class="login" method="POST" action="">
	<p>Si ya estás registrado puedes acceder con tu usuario y contraseña.</p>
	<input type="text" name="username" placeholder="Usuario" autofocus>
	<input type="password" name="password" placeholder="Contraseña">
	<button type="submit" name="login">Entrar</button>
	<?php if (mroWebmail()) { echo "<p><a class='password' href='".url()."/u/?password'>¿Has perdido tu contraseña?</a></p>"; } ?>
</form>