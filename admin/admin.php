<title>Panel de control</title>
<h2>Panel de control</h2>
<p>Este es el panel de administración. Desde aquí puedes controlar y configurar la actividad de Mercurio.</p>
<?php include 'breaches.php'; ?>
<canvas id="chart"></canvas>
<?php
$seconds = 86400;
$days = array();
$i = 0;
while ($i < 7) {
	$day = time()-($seconds*$i);
	$days[] = date('d/m', (int)$day); 
	$i++;
}
$posts = array();
$i = 1;
while ($i < 8) {
	$range = time()-($seconds*$i);
	$rangeto = $range+$seconds;
	$sql = $conn->query("SELECT * FROM posts WHERE stamp >= '$range' AND stamp <= '$rangeto'");
	$posts[] = $sql->rowCount();
	$i++;
}
$comments = array();
$i = 1;
while ($i < 8) {
	$range = time()-($seconds*$i);
	$rangeto = $range+$seconds;
	$sql = $conn->query("SELECT * FROM comments WHERE stamp >= '$range' AND stamp <= '$rangeto'");
	$comments[] = $sql->rowCount();
	$i++;
}
$users = array();
$i = 1;
while ($i < 8) {
	$range = time()-($seconds*$i);
	$rangeto = $range+$seconds;
	$sql = $conn->query("SELECT * FROM users WHERE since >= '$range' AND since <= '$rangeto'");
	$users[] = $sql->rowCount();
	$i++;
}
?>
<script src="../assets/js/Chart.js"></script>
<script type="text/javascript">
var ctx = document.getElementById('chart').getContext('2d');
var chart = new Chart(ctx, {
type: 'line',
  data: {
    datasets: [{
		label: 'Entradas',
		backgroundColor: 'rgb(9, 95, 95, 0.25)',
		borderColor: 'rgb(160, 9, 95)',
		data: <?php echo json_encode($posts); ?>
    }, {
		label: 'Usuarios',
		backgroundColor: 'rgb(9, 160, 95, 0)',
		borderColor: 'rgb(9, 160, 95)',
		data: <?php echo json_encode($users); ?>,
		type: 'line'
    }, {
    	label: 'Comentarios',
    	backgroundColor: 'rgb(255, 95, 160, 0)',
      	borderColor: 'rgb(90, 165, 255)',
      	data: <?php echo json_encode($comments); ?>,
      	type: 'line'
    }],
    labels: <?php echo json_encode($days); ?>
  },
  options:{}
});
</script>