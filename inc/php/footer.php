<footer>
	<!--Remover la marca de agua y la licencia de Creative Commons va en contra de la propia licencia. Por favor, no lo hagas.-->
	<a class="ccommons" title="Esta obra está bajo una licencia de Creative Commons Reconocimiento-NoComercial-CompartirIgual 4.0 Internacional" rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Esta obra está bajo una licencia de Creative Commons Reconocimiento-NoComercial-CompartirIgual 4.0 Internacional" style="border-width:0" src="<?php echo url(); ?>/inc/img/88x31.png" /></a>
	<span id="watermark"><a target="_blank" href="https://github.com/facuonline/mercurio"><img title="Este sitio funciona con Mercurio." alt="Mercurio" src="<?php echo url(); ?>/inc/img/mercurio26.png"></a> <?php mroNumber(); ?></span>
	<div class="det">
		<p><?php $pid = microtime(); $pid = explode(' ', $pid); $pid = $pid[1] + $pid[0]; $finish = $pid; $total_time = round(($finish - $start), 4); echo 'Proceso '.getmypid().' '.date('Y/m/d H:i:s').'. T: '.$total_time.'s'; ?></p> &pi;
	</div>
</footer>