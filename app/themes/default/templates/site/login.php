<?=B::form(
	array(
		'action' => SITE_URL . 'login',
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