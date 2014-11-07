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

$hooks = __NAMESPACE__ . '\Hooks::';

return [

	__NAMESPACE__ . '\Album' => [

		'as:carousel' => $hooks . 'render_album_as_carousel'

	],

	__NAMESPACE__ . '\Photo' => [

		'as:carousel-item' => $hooks . 'render_album_photo_as_carousel_item'

	]

];