<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework Project Test')
	),
	'body' => B::body(
		B::header(
			B::h1('Dashboard - Pages'),
			T::get('admin/site/nav')
		),
		B::section(
			B::h3('Pages'),
			B::a(array('href'=> SITE_URL .'admin/addpage'), 'Add Page'),
			B::ul(join(array_map(
				function($v) {
					return B::li(V::get('admin/pages/brief', array('page' => $v)));
				},
				$pages
			)))
		),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));