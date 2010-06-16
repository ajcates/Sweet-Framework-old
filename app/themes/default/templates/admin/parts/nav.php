<?=B::nav(B::ul(
	B::li(B::a(
		array('href' => SITE_URL . 'admin'),
		'Dashboard'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/pages'),
		'pages'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/users'),
		'users'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/logout'),
		'logout'
	))
));