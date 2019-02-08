<div class="parent">
<form class="child comment message" action="" method="POST">
	<textarea name="message_body" placeholder="Escribe aquí tu mensaje para @<?php echo $_GET['u']; ?>. Los mensajes privados no se pueden editar o borrar."></textarea>
	<button type="submit" name="message">Enviar mensaje</button>
	<a class="button delete fixer">Cancelar</a>
	<script type="text/javascript">
		function toggleFixed() {
		   adjustWidth();
		   $(".child").toggleClass("fixed");
		 }

		 function adjustWidth() {
		   var parentwidth = $(".parent").width();
		   $(".child").width(parentwidth);
		 }
		 $(function() {
		   $(".fixer").click(
		     function() {
		       toggleFixed();
		     });
		   $(window).resize(
		     function() {
		       adjustWidth();
		     })
		 })
	</script>
</form>
</div>