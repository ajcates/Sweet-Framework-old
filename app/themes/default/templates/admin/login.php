<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework Backend')
	),
	'body' => B::body(
		B::header(
			B::h1('Login'),
			T::get('/admin/site/nav')
		),
		T::get('site/login'),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));