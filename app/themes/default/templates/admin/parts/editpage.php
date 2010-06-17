<?=B::form(
	array(
		'action' => SITE_URL . 'admin/page/edit/do',
		'method' => 'POST'
	),
	B::label('Title:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'title',
			'value' => $page->title
		)
	),
	B::label('Slug:'),
	B::input(
		array(
			'type' => 'text',
			'name' => 'slug',
			'value' => $page->slug
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
		$page->content
	),
	B::br(),
	B::input(
		array(
			'type' => 'submit',
			'value' => 'Edit Page'
		)
	)
);