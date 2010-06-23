<?=join(array(
	B::section(
		B::h4($test),
		B::ul(join(array_map(
			function($page) {
				return B::li($page->title);
			},
			$pages
		)))
	)
));