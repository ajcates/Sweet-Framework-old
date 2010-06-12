<?=B::nav(array(
	'text' => B::ul(array(
		'items' => array(
			B::a(array(
				'text' => 'Dashboard',
				'href' => URL
			)),
			B::a(array(
				'text' => 'Projects',
				'href' => SITE_URL . 'projects'
			))
		)
	))
));