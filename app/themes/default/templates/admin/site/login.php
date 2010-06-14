<?=B::form(
	array(
		'action' => SITE_URL . 'users/dologin',
		'method' => 'POST'
	),
	B::h3('Login'),
	B::label('Username:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'username'
		)
	),
	B::br(),
	B::label('Password:'),
	B::input(
		array(
			'type' => 'password',
			'name' => 'password'
		)
	),
	B::input(
		array(
			'type' => 'submit',
			'value' => 'Submit'
		)
	)
);