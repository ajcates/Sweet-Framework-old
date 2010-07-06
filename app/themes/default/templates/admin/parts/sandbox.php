<?=join(array(
	B::section(
		B::h4($test),
		B::ul(join(array_map(
			function($page) {
				return B::li(
					B::h3($page->title),
					B::div(
						array('class' => 'user'),
						print_r($page->tags, true),
						print_r($page->user, true)
					)
				);
			},
			$pages
		)))
	)
));