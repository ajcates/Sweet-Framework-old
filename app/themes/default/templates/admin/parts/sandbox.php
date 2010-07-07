<?=join(array(
	B::section(
		B::h4($test),
		B::ul(join(array_map(
			function($page) {
				return B::li(
					B::h2($page->title),
					B::dl(
						B::dt('User:'),
						B::dd($page->user->username),
						B::dt('Tags:'),
						B::dd(B::ul(
							join(
								array_map(
									function($tag) {
										return B::li($tag->tag->name);
									},
									$page->tags
								)
							)
						))
					)
				);
			},
			$pages
		)))
	)
));