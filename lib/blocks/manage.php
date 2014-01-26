<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Images\Albums;

class ManageBlock extends \Icybee\Modules\Nodes\ManageBlock
{
	static protected function add_assets(\Brickrouge\Document $document)
	{
		parent::add_assets($document);

		$document->css->add('manage.css');
	}

	public function __construct(Module $module, array $attributes=array())
	{
		parent::__construct
		(
			$module, $attributes + array
			(
				self::T_COLUMNS_ORDER => array('title', 'is_online', 'photos', 'uid', 'updated_at')
			)
		);
	}

	/**
	 * Adds the following columns:
	 *
	 * - `photos`: An instance of {@link ManageBlock\PhotosColumn}.
	 */
	protected function get_available_columns()
	{
		return array_merge(parent::get_available_columns(), array
		(
			'photos' => __CLASS__ . '\PhotosColumn'
		));
	}
}

namespace Icybee\Modules\Images\Albums\ManageBlock;

use Icybee\ManageBlock\Column;

/**
 * Representation of the `photos` column.
 */
class PhotosColumn extends Column
{
	public function __construct(\Icybee\Modules\Images\Albums\ManageBlock $manager, $id, array $options=array())
	{
		parent::__construct
		(
			$manager, $id, $options + array
			(
				'title' => 'Photos'
			)
		);
	}

	public function render_cell($record)
	{
		global $core;

		$html = '';

		$photos = $core->models['images.albums/photos']->filter_by_nid($record->nid)->order('weight');
		$count = $photos->count;
		$photos = $photos->limit(10)->all;

		if ($photos)
		{
			$html .= '<div class="photos"><div class="photos-inner">';

			foreach ($photos as $photo)
			{
				/* @var $image \ICanBoogie\Modules\Images\Image */

				$image = $photo->image;

				$html .= $image->thumbnail('$icon')->to_element
				(
					array
					(
						'data-popover-image' => $image->thumbnail('$popover')->url,
						'class' => null
					)
				);
			}

			$html .= '</div>';

			if ($count > count($photos))
			{
				$html .= '&nbsp;…';
			}

			$html .= '</div>';
		}

		return $html;
	}
}