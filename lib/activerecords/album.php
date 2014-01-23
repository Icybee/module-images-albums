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

use ICanBoogie\ActiveRecord;

/**
 * An album of photos.
 */
class Album extends \Icybee\Modules\Nodes\Node implements \IteratorAggregate
{
	public $posterid;
	public $description;

	/**
	 * Returns an iterator for the photos associated with the album.
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->photos);
	}

	/**
	 * Returns the photos associated with the album.
	 *
	 * @return array[]Photo
	 */
	protected function lazy_get_photos()
	{
		$records = ActiveRecord\get_model('images.albums/photos')
		->filter_by_nid($this->nid)
		->order('weight')
		->all;

		#

		$keys = array();

		foreach ($records as $record)
		{
			$keys[$record->imageid] = $record;
		}

		if ($keys)
		{
			$images = ActiveRecord\get_model('images')->find_using_constructor(array_keys($keys));

			foreach ($images as $key => $image)
			{
				$keys[$key]->image = $image;
			}
		}

		return $records;
	}
}