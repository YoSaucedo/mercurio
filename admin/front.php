<title>Diseñar portada</title>
<h2>Portada</h2>
<p>La <a href="<?php echo url(); ?>">portada</a> está pensada para ser un escaparate del sitio. Cuídala.</p>
<?php if (isset($_GET['how'])) { ?>
	<p><a href="?front">Volver al diseño de portada</a>.</p>
	<h3>Qué historias promocionar:</h3>
	<p>Con una selección cuidada de historias en la portada puedes llegar hasta los usuarios que desees. Si los usuarios ven que un cierto tipo de contenido llega a la portada comenzarán a crear más contenido parecido, y si los visitantes ven esas historias en portada tendrán la percepción de que es el contenido general del sitio.</p>
	<p><strong>Es importante</strong> que como administrador mantengas la portada renovada y fresca cada cierto tiempo. También es importante que mantengas la promoción de contenido en secreto, tus usuarios no necesitan saber que la portada es elección tuya.</p>
	<p>En ella los usuarios encontrarán todas las secciones de <?php echo mroName(); ?> y tres historias dentro de cada sección.</p>
	<p>Por defecto la portada clasifica las historias en base a su popularidad (número de impresiones), pero tú puedes enviar una historia a la portada aunque no sea popular, o lanzar una sección hasta arriba.</p>
	<h3>Secciones:</h3>
	<p>Cuando administras una sección te encontrarás un botón como este:</p>
	<form><a class="button" href="?front&how">Primera posición</a></form>
	<p>Este botón hará que la sección se coloque en primer puesto. Si anteriormente habías colocado una sección en primer puesto, esta quedará en segundo lugar, y así sucesivamente con otras secciones.</p>
	<p>Las secciones en primer puesto pueden volver a su posición orgánica (automática), pulsando en el botón:</p>
	<form><a class="button" href="?front&how">Posición orgánica</a></form>
	<h3>Historias:</h3>
	<p>La portada organiza las historias en base a su popularidad.</p>
	<p>Cuando administras una historia, verás este botón:</p>
	<form><a class="button" href="?front&how">Enviar a la portada</a></form>
	<p>Puede que esta historia ya esté en portada si es popular, pero si la enviamos a la portada subirá al primer puesto de las tres historias, igual que con las secciones, y se mantendrá allí más tiempo que las demás.</p>
	<p>También puede ser que aunque la envies a la portada no aparezca, pues la portada impone ciertas condiciones para garantizar contenido reciente e interesante, no entrarán en la portada:</p>
	<ul>
		<li>Historias no actualizadas desde hace más de una semana desde que llegan a portada.</li>
		<li>Historias que hayan sido actualizadas sin contenido para mantenerse en portada.</li>
	</ul>
	<p>Para retirar a la historia de la portada, sencillamente pulsa el botón:</p>
	<form><a class="button" href="?front&how">Retirar de la portada</a></form>
	<p>Y la historia volverá a posicionar por popularidad.</p>
<?php } else { ?>
	<p><a href="?front&how">Cómo funciona</a>.</p>
	<form class="front" method="POST" action="" enctype="multipart/form-data">
		<div class="user_banner" style="background-image: url('../assets/img/front.jpg');">
	        <input class="file" id="file" type="file" name="front" accept=".jpg, .png, .gif, .webp">
	        <label class="button avatar" for="file"><i class="im im-upload"></i></label>
	        <span class="username">Subir imagen de portada.</span>
	        <span class="username">Tamaño recomendado 1300x900px.</span>
	        <span class="username">Peso máximo de archivo <?php mroMaxFileSize(); ?>MB.</span>
	        <button type="submit" name="front">Subir imagen</button>
	    </div>
	    <script type="text/javascript">
	        $('#file').change(function() {
	            $(this).next('label').append("<p>Tu archivo ha sido seleccionado.</p>");
	        })
	    </script>
		<?php $sections = $conn->query("SELECT * FROM sections ORDER BY front DESC");
		while ($section = $sections->fetch()) { ?>
			<section class="front" style="background-color: #<?php echo $section['color']; ?>;" id="<?php echo $section['name']; ?>">
			<div class="wrapper">
				<a href="?section=<?php echo $section['slug']; ?>"><h2><?php echo $section['name']; ?></h2></a>
				<?php if ($section['front'] != '0') { echo "(posicionada)"; }
				$week = time() - 604800;
				$posts = $conn->query("SELECT * FROM posts WHERE section = '$section[id]' AND updated_m != 'nochange' AND updated > '$week' ORDER BY front DESC, pop DESC LIMIT 3");
				if ($posts->rowCount() < 3) {
					$alltime = $conn->query("SELECT * FROM posts WHERE section = '$section[id]' AND updated_m != 'nochange' ORDER BY front DESC, pop DESC, id DESC LIMIT 3"); ?>
					<ol>
						<?php while ($post = $alltime->fetch()) { ?>
							<li>
								<a href="?post=<?php echo post['id']; ?>"><?php echo $post['title']; ?></a>
								<?php if ($post['front'] != '0') { echo "(posicionada)"; } else { echo "(popular)"; } ?>
							</li>
						<?php } ?>
					</ol>
				<?php } else { ?>
					<ol>
						<?php while ($post = $posts->fetch()) { ?>
						<?php } ?>
					</ol>
				<?php } ?>
			</div>
		</section>
		<?php } ?>
	</form>
<?php } ?>