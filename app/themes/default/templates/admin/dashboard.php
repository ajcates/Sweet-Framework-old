<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework Project Test')
	),
	'body' => B::body(
		B::header(
			B::h1('Dashboard'),
			T::get('admin/site/nav')
		),
		B::section(
			B::h3('Pages')
		),
		B::section(
			B::h3('meh')
		),
		B::section(
			B::h3('Users'),
			B::ul(join(array_map(
				function($v) {
					return B::li(V::get('users/brief', array('user' => $v)));
				},
				M::User()->limit(10)->all()
			)))
		),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));