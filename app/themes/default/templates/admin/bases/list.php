<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework CMS - ' . $title)
	),
	'body' => B::body(
		B::header(
			B::h1('SweetFramework - ' . $title),
			T::get('admin/parts/nav')
		),
		ifthereshow(@$message, 
			B::div(array('class' => 'message'), @$message)
		),
		B::div(array('class' => 'list main'),
			B::div(array('class' => 'list actions'), $actions),
			B::ul(join(array_map($itemsEach, $items)))
		),
		ifthereshow(@$sidebar,
			B::aside(@$sidebar)
		),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));