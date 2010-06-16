<?=join(array(
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
	)
));