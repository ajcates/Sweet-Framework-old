<?=B::form(
	array(
		'action' => SITE_URL . 'admin/user/add/do',
		'method' => 'POST'
	),
	B::label('Full Name:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'fullname'
		)
	),
	B::br(),
	B::label('Username:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'username'
		)
	),
	B::br(),
	B::label('Email:'),
	B::input(
		array(
			'type' => 'email',
			'name' => 'email'
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
	B::br(),
	B::label('Password Check:'),
	B::input(
		array(
			'type' => 'password',
			'name' => 'passwordcheck'
		)
	),
	B::br(),
	B::input(
		array(
			'type' => 'submit',
			'value' => 'Add User'
		)
	)
);