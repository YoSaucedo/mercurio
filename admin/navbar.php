</head>
<body>
	<header>
		<a href="<?php echo url(); ?>" id="logo">
			<img alt="<?php mroName(); ?>" title="Inico" src="<?php echo url(); mroLogo();?>">
		</a>
		<nav>
			<ul class="menu">
					<a href="<?php echo url(); ?>/admin/"><li><i class="im im-control-panel"></i><span>Inicio</span></li></a>
					<a href="?config"><li><i class="im im-control-panel"></i><span><?php echo mroName(); ?></span></li></a>
					<a href="?sections"><li><i class="im im-bookmark"></i><span>Secciones</span></li></a>
					<a href="?front"><li><i class="im im-newspaper-o"></i><span>Portada</span></li></a>
					<a href="<?php echo url()."/u"; ?>"><li><i class="im im-angle-left"></i><span>Volver</span></li></a>
			</ul>
			<ul class="notifications">
				<?php uNotifications(); ?>
			</ul>
		</nav>
	</header>
	<div class="alerts"><?php errors(); successes(); ?></div>