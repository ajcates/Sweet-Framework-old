<?=B::xhtml5(array(
	'head' => B::head(array(
		'title' => 'SweetFramework Project Test',
	)),
	'body' => B::body(array(
		'header' => B::header(array(
			'title' => 'Project Test',
			'nav' => T::get('site/nav')
		)),
		'content' => array(
			B::h1(array(
				'text' => 'Projects'
			)),
			B::ul(array(
				'items' => D::log(array_map(
					function($v) {
						return V::get('project/detail', array('project' => $v));
					},
					$projects
				), 'projects')
			))
		),
		'footer' => B::footer(array(
			'text' => 'Copyright ajcates ' . date('Y')
		))
	))
));