<?=B::xhtml5(array(
	'head' => B::head(array(
		'title' => 'SweetFramework Project Test',
	)),
	'body' => B::body(array(
		'header' => B::header(array(
			'title' => 'Dashboard',
			'nav' => T::get('site/nav')
		)),
		'content' => array(
			B::section(array(
				'content' => array(
					B::h3(array(
						'text' => 'Projects'
					)),
					B::ul(array(
						'items' => D::log(array_map(
							function($v) {
								return V::get('project/brief', array('project' => $v));
							},
							$projects
						), 'projects')
					))
				)
			)),
			B::section(array(
				'content' => array(
					B::h3(array(
						'text' => 'Users'
					)),
					B::ul(array(
						'items' => D::log(array_map(
							function($v) {
								return V::get('users/brief', array('user' => $v));
							},
							M::Users()->limit(10)->all()
						), 'projects')
					))
				)
			))
		),
		'footer' => B::footer(array(
			'text' => 'Copyright ajcates ' . date('Y')
		))
	))
));