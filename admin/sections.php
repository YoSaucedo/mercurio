<title>Secciones</title>
<h2>Secciones</h2>
<p>Las secciones son las categorías bajo las que los usuarios pueden publicar, ayudan a distribuir mejor el contenido. Es importante que administres bien las categorías para que tus usuarios las usen adecuadamente.</p>
<?php if (isset($_GET['new'])) { ?>
	<form method="POST" action="">
		<input type="text" name="name" placeholder="Nombre">
		<input type="text" name="color" class="jscolor">
		<input type="text" name="slug" placeholder="slug">
		<textarea placeholder="Descripción de la sección." name="description"></textarea>
		<input type="checkbox" name="adminonly"><label> Sólo administradores pueden publicar.</label>
		<button type="submit" name="section_create">Añadir sección</button>
	</form>
<?php } else { ?>
	<p><a href="?sections&new">Añadir nueva sección.</a></p>
	<div class="sections">
	<?php $sql = $conn->query("SELECT * FROM sections ORDER BY id DESC");
	while ($section = $sql->fetch()) { ?>
		<div class="sectioncard" style="background-color: #<?php echo $section['color']; ?>;">
			<a href="?section=<?php echo $section['slug']; ?>">
			<h3><?php echo $section['name']; ?></h3>
			<p><?php echo $section['description']; ?></p>
			<?php if ($section['adminonly'] == '1') { ?>
				<p class="adminonly">Sección para administradores.</p>
			<?php } ?>
			</a>
		</div>
	<?php } ?>
	</div>
	<canvas id="chart"></canvas>
	<?php
	$sections = array();
	$colors = array();
	$posts = array();
	$sql = $conn->query("SELECT * FROM sections ORDER BY id DESC");
	while ($section = $sql->fetch()) {
		$sections[] = htmlspecialchars($section['name']);
		$colors[] = '#'.$section['color'];
		$countposts = $conn->query("SELECT id FROM posts WHERE section = '$section[id]'");
		$posts[] = $countposts->rowCount();
	}?>
	<script src="../assets/js/Chart.js"></script>
	<script src="../assets/js/masonry.pkgd.min.js"></script>
	<script type="text/javascript">
		$('.sections').masonry({
			// options
			itemSelector: '.sectioncard',
			columnWidth: 0
		});
		new Chart(document.getElementById("chart"), {
		    type: 'doughnut',
		    data: {
		      labels: <?php echo json_encode($sections); ?>,
		      datasets: [
		        {
		          label: "Población",
		          backgroundColor: <?php echo json_encode($colors); ?>,
		          data: <?php echo json_encode($posts); ?>
		        }
		      ]
		    },
		    options: {
		      title: {
		        display: true,
		        text: 'Historias por sección'
		      }
		    }
		});
	</script>
<?php } ?>