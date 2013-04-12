<?php

namespace Icybee\Modules\Images\Albums;

return array
(
	'api:images.albums:photo' => array
	(
		'pattern' => '/api/images.albums/photos/<id:\d+>',
		'controller' => __NAMESPACE__ . '\UpdatePhotoOperation',
		'via' => 'POST'
	),

	'api:images.albums:photo:delete' => array
	(
		'pattern' => '/api/images.albums/photos/<id:\d+>',
		'controller' => __NAMESPACE__ . '\DeletePhotoOperation',
		'via' => 'DELETE'
	),

	'api:images.albums:photo/save' => array
	(
		'pattern' => '/api/images.albums/photos',
		'controller' => __NAMESPACE__ . '\UpdatePhotoOperation',
		'via' => 'POST'
	)
);