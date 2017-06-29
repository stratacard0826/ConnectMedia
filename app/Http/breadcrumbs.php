<?php

//Home
Breadcrumbs::register('/', function($breadcrumbs){

	$breadcrumbs->push('Dashboard', '/', [ 'icon' => 'fa fa-home' ]);

});

//Administration
Breadcrumbs::register('/admin/users', function($breadcrumbs){

	$breadcrumbs->parent('/');
	$breadcrumbs->push('Administration', '/admin/users', [ 'icon' => 'fa fa-users' ]);
	$breadcrumbs->push('Users', '/admin/users', [ 'icon' => 'fa fa-user' ]);

});


//Administration :: Users :: Add
Breadcrumbs::register('/admin/users/add', function($breadcrumbs){

	$breadcrumbs->parent('/admin/users');
	$breadcrumbs->push('Add User', '/admin/users/add', [ 'icon' => 'fa fa-plus-circle' ]);

});


//Administration :: Users :: Add
Breadcrumbs::register('/admin/users/edit', function($breadcrumbs, $id){

	$breadcrumbs->parent('/admin/users');
	$breadcrumbs->push('Add User', '/admin/users/edit/' . $id , [ 'icon' => 'fa fa-plus-circle' ]);

});
