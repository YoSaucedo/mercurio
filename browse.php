<?php 
$section = $_GET['section'];
$getsection = $conn->query("SELECT * FROM sections WHERE slug = '$section'");

while ($section = $getsection->fetch()) {

$browse = $section['id'];
if (isset($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$limit = 12;
$offset = ($pageno-1) * $limit;
$getposts = $conn->query("SELECT * FROM posts WHERE section = '$browse'");
$total_rows = $getposts->rowCount();
$total_pages = ceil($total_rows/$limit);
$posts = $conn->query("SELECT * FROM posts WHERE section = '$browse' ORDER BY stamp DESC LIMIT $offset, $limit");

?>
<title><?php echo $section['name']; ?></title>
<div class="section_single">
<a href="?section=<?php echo $section['name']; ?>">
	<div class="sectioncard" style="background-color: #<?php echo $section['color']; ?>;">
		<h3><?php echo $section['name']; ?></h3>
		<p><?php echo $section['description']; ?></p>
		<?php if ($section['adminonly'] == '1') { ?>
			<p class="adminonly">Sección para administradores.</p>
		<?php } ?>
	</div>
</a>
</div>
<section class="content">
<?php } ?>
<div class="posts">
<?php while ($post = $posts->fetch()) { include 'inc/php/single_post.php'; } ?>
</div>
<?php
if (isset($total_pages) && $total_pages > 1) { ?>
<div class="page-load-status">
	<p class="infinite-scroll-request"><i class="im im-spinner" title="Cargando"></i></p>
	<p class="infinite-scroll-last"><i class="im im-archive" title="¡Has llegado al final!"></i></p>
	<p class="infinite-scroll-error"><i class="im im-cloud" title="¿Qué has hecho?"></i></p>
</div>
<ul class="hidden pagination">
<?php $pageno = 0;
while ($pageno < $total_pages) { ?>
		<li>
			<a class="<?php if (isset($_GET['pageno']) && $pageno+1 == $_GET['pageno']) { echo "current"; } if($pageno+1 !== 1 && $_GET['pageno'] < $pageno+1) { echo "pagination__next"; } ?>" href="?section=$section?pageno=<?php echo $pageno+1; ?>"><?php echo $pageno+1 ?></a>
		</li>
	<?php $pageno++; 
} ?>
</ul>
</section>
<?php }	?>
<script src="inc/js/masonry.pkgd.min.js"></script>
<script src="inc/js/infinite-scroll.pkgd.min.js"></script>
<script type="text/javascript">
	var $grid = $('.posts').masonry({
		itemSelector: '.single_post',
		columnWidth: 260
	});
	var msnry = $grid.data('masonry');
	$grid.infiniteScroll({
		path: '.pagination__next',
		append: '.posts',
		status: '.page-load-status',
		outlayer: msnry,
	});
</script>