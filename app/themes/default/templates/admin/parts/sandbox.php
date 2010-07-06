<?=join(array(
	B::section(
		B::h4($test),
		B::ul(join(array_map(
			function($page) {
				return B::li(
					B::h3($page->title),
					B::dl(
						B::dt('User'),
						B::dd($page->user->username),
						B::dt('Tags'),
						B::dd(print_r($page->tags, true))
					)
				);
			},
			$pages
		)))
	)
));