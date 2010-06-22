<?=B::nav(B::ul(
	B::li(B::a(
		array('href' => SITE_URL . 'admin'),
		'Dashboard'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/sandbox'),
		'Sandbox'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/pages'),
		'Pages'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/users'),
		'Users'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin/logout'),
		'Logout'
	))
));