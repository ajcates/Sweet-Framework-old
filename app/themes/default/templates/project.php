<?=B::xhtml5(array(
	'head' => B::head(array(
		'title' => 'SweetFramework Project Test',
	)),
	'body' => B::body(array(
		'header' => B::header(array(
			'title' => 'Project - ' . $project->name,
			'nav' => T::get('site/nav')
		)),
		'content' => V::get('project/detail', array('project' => $project)),
		'footer' => B::footer(array(
			'text' => 'Copyright ajcates ' . date('Y')
		))
	))
));