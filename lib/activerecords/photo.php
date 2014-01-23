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
 * A photo of an album.
 */
class Photo extends ActiveRecord
{
	/**
	 * Identifier of the photo.
	 *
	 * @var int
	 */
	public $id;

	/**
	 * Identifier of the album.
	 *
	 * @var int
	 */
	public $nid;

	/**
	 * Identifier of the image.
	 *
	 * @var int
	 */
	public $imageid;

	/**
	 * Title of the photo.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Caption of the photo.
	 *
	 * @var string
	 */
	public $caption;

	/**
	 * Weight of the photo in the album.
	 *
	 * @var int
	 */
	public $weight;

	/**
	 * Returns the associated image as a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->image;
	}

	/**
	 * Returns the image associated with the photo.
	 *
	 * @return \Icybee\Modules\Images\Image|null Returns the associated image or `null` if there
	 * is no image associated.
	 */
	protected function lazy_get_image()
	{
		return $this->imageid ? ActiveRecord\get_model('images')->find($this->imageid) : null;
	}
}