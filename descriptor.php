<?php

namespace Icybee\Modules\Images\Albums;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module;

return array
(
	Module::T_CATEGORY => 'organize',
	Module::T_DESCRIPTION => "Organise images in albums.",
	Module::T_EXTENDS => 'nodes',
	Module::T_MODELS => array
	(
		'primary' => array
		(
			Model::EXTENDING => 'nodes',
			Model::SCHEMA => array
			(
				'fields' => array
				(
					'poster_id' => 'foreign',
					'description' => 'text'
				)
			)
		),

		'photos' => array
		(
			Model::CLASSNAME => 'ICanBoogie\ActiveRecord\Model',
			Model::SCHEMA => array
			(
				'fields' => array
				(
					'id' => 'serial',
					'nid' => 'foreign',
					'image_id' => 'foreign',
					'title' => array('varchar', 80),
					'caption' => 'text',
					'link' => 'varchar',
					'is_target_blank' => 'boolean',
					'weight' => array('integer', 'unsigned' => true, 'default' => 0)
				)
			)
		)
	),

	Module::T_NAMESPACE => __NAMESPACE__,
	Module::T_REQUIRES => array
	(
		'images' => '1.0'
	),

	Module::T_TITLE => 'Albums',
	Module::T_VERSION => '1.0'
);