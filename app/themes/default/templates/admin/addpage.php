<?=B::get('xhtml5', array(
	'head' => B::head(
		B::title('SweetFramework Project Test')
	),
	'body' => B::body(
		B::header(
			B::h1('Dashboard - Pages'),
			T::get('admin/site/nav')
		),
		B::section(
			B::h3('Add Page'),
			B::form(
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
			)
		),
		B::footer(
			B::p('Copyright ajcates ' . date('Y'))
		)
	)
));