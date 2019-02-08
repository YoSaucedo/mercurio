<style type="text/css">
	header, .user_banner{
		background-color: #<?php mroHeaderBg(); ?>;
	}
	a {
		color: #<?php mroColor(); ?>;
	}
	form button, a.button {
		background-color: #<?php mroColor(); ?>;
		transition: all 1s;
	}
	form button:hover, a.button:hover{
		background-color: #<?php mroHeaderBg(); ?>;
		color: #<?php mroColor(); ?>;
	}
	section.login form.register{
		background: linear-gradient(#<?php mroColor(); ?>, #<?php mroHeaderBg(); ?>);
	}
	<?php if (uUser() && uDarkMode()) { ?>
		body { background-color: #181818; color: #f3f3f3; }
		.commented button, .single_post .stars a, .single_post .comments a, .front a h2{ color: #f8f8f8; }
	<?php } else { ?>
		body { background-color: #f3f3f3; color: #181818; }
	<?php } ?>
</style>