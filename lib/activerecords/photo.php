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
class Photo extends ActiveRecord implements \Brickrouge\CSSClassNames
{
	use \Brickrouge\CSSClassNamesProperty;

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
	public $image_id;

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
	 * A URL link.
	 *
	 * @var string
	 */
	public $link;

	/**
	 * Weight of the photo in the album.
	 *
	 * @var int
	 */
	public $weight;

	public function __construct($model='images.albums/photos')
	{
		parent::__construct($model);
	}

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
		return $this->image_id ? ActiveRecord\get_model('images')->find($this->image_id) : null;
	}

	protected function lazy_get_album()
	{
		return $this->nid ? ActiveRecord\get_model('images.albums')[$this->nid] : null;
	}

	/**
	 * Returns the CSS class names of the node.
	 *
	 * @return array[string]mixed
	 */
	protected function get_css_class_names()
	{
		$id = $this->id;
		$image_id = $this->image_id;

		return array
		(
			'type' => 'album-photo',
			'id' => $id ? "album-photo-{$id}" : null,
			'album-photo-image' => $image_id ? "album-photo-image-{$image_id}" : null
		);
	}
}