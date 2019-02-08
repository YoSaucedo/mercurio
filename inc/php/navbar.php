</head>
<body>
	<header>
		<a href="<?php echo url(); ?>" id="logo">
			<img alt="<?php mroName(); ?>" title="Inico" src="<?php echo url(); mroLogo();?>">
		</a>
		<nav>
			<ul class="menu">
				<?php 
				if (uUser()) { ?>
					<a href="<?php echo url(); ?>/u/?feed"><li><i class="im im-smiley-o"></i><span>Feed</span></li></a>
					<a href="<?php echo url(); ?>/u"><li><i class="im im-user-circle"></i>
						<span>@<?php echo $_SESSION['username']; ?></span>
					</li></a>
					<a href="<?php echo url(); ?>/post"><li><i class="im im-pencil"></i><span>Escribir</span></li></a>
					<a href="<?php echo url(); ?>/search.php"><li><i class="im im-magnifier"></i><span>Buscar</span></li></a>
					<?php if (uAdmin()) { ?>
					<a href="<?php echo url(); ?>/admin"><li><i class="im im-control-panel"></i><span>Admin</span></li></a>
					<?php } ?>
				<?php } else { ?>
					<a href="<?php echo url(); ?>/u"><li>Entrar</li></a>
				<?php } ?>
			</ul>
			<ul class="notifications">
				<?php uNotifications(); ?>
			</ul>
		</nav>
	</header>
	<div class="alerts"><?php errors(); successes(); ?></div>