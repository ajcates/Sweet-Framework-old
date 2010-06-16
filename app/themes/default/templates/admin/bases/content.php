<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework CMS - ' . $title)
	),
	'body' => B::body(
		B::header(
			B::h1('Dashboard - ' . $title),
			T::get('admin/parts/nav')
		),
		ifthereshow(@$message, 
			B::div(array('class' => 'message'), @$message)
		),
		B::div(
			array('class' => 'content main'),
			$content
		),
		ifthereshow(@$sidebar,
			B::aside(@$sidebar)
		),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));

/*
@todo
Make this a more dynamic form builder to show off how cool blocks reall can be.
*/