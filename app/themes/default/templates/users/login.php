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
			T::get('site/login')
		)
	)
));