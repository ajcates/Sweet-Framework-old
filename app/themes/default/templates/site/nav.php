<?=B::nav(B::ul(
	B::li(B::a(
		array('href' => URL),
		'Dashboard'
	)),
	B::li(B::a(
		array('href' => SITE_URL . 'admin'),
		'Admin'
	))
));