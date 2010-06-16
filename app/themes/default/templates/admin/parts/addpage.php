<?=B::form(
	array(
		'action' => SITE_URL . 'admin/doaddpage',
		'method' => 'POST'
	),
	B::label('Title:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'title'
		)
	),
	B::label('Slug:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'slug'
		)
	),
	B::br(),
	B::label('Content:'),
	B::textarea(
		array(
			'name' => 'content',
			'rows' => 10,
			'cols' => 45
		),
		null
	),
	B::br(),
	B::input(
		array(
			'type' => 'submit',
			'value' => 'Add Page'
		)
	)
);