<?=B::form(
	array(
		'action' => SITE_URL . 'admin/user/edit/do/' . $user->id,
		'method' => 'POST'
	),
	B::label('Full Name:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'fullname',
			'value' => $user->fullname
		)
	),
	B::br(),
	B::label('Username:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'username',
			'value' => $user->username
		)
	),
	B::br(),
	B::label('Email:'),
	B::input(
		array(
			'type' => 'email',
			'name' => 'email',
			'value' => $user->email
		)
	),
	B::br(),
	B::label('Password: (leave blank for same password)'),
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
			'value' => 'Edit User'
		)
	)
);