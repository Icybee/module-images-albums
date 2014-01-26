<?php

/*
 * This file is part of the Brickrouge package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Images\Albums;

class PhotoTest extends \PHPUnit_Framework_TestCase
{
	public function test_css_class_property()
	{
		$photo = Photo::from([

			'id' => '123',
			'image_id' => '456'

		]);

		$this->assertEquals('album-photo album-photo-123 album-photo-image-456', $photo->css_class);
	}
}