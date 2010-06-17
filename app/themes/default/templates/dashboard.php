<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework Project Test')
	),
	'body' => B::body(
		B::header(
			B::h1('Dashboard'),
			T::get('site/nav')
		),
		B::section(
			B::h3('Projects'),
			B::ul(join(array_map(
				function($v) {
					return B::li(V::get('project/brief', array('project' => $v)));
				},
				array()//$projects
			)))
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