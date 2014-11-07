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

use Brickrouge\Element;

class Hooks
{
	static public function render_album_as_carousel(Album $album, array $options=[])
	{
		global $core;

		$rendered_photos = '';

		foreach ($album as $photo)
		{
			$rendered_photos .= $core->render($photo, 'as:carousel-item', $options);
		}

		$options += [

			'method' => 'slide',
			'autoplay' => false,
			'autodots' => false

		];

		return new Element('div', [

			Element::IS => 'Carousel',

			Element::INNER_HTML => <<<EOT
<div class="carousel-inner">
		$rendered_photos
</div>

<a class="carousel-control left" href="#" data-slide="prev">&lsaquo;</a>
<a class="carousel-control right" href="#" data-slide="next">&rsaquo;</a>
EOT
			,

			'class' => 'carousel',

			'data-method' => $options['method'],
			'data-autoplay' => $options['autoplay'] ?: null,
			'data-autodots' => $options['autodots'] ?: null

		]);
	}

	static public function render_album_photo_as_carousel_item(Photo $photo, array $options=[])
	{
		$template = <<<EOT
<div class="item" data-href="#{@link}" data-title="#{@image.title}">
	#{@image=}

	<!--div class="caption">
		<div class="title">#{@title}</div>
		<div class="description">#{@caption=}</div>
		<p:if test="@link">
		<a href="#{@link}">Voir</a>
		</p:if>
	</div-->
</div>
EOT;
		$engine = new \Patron\Engine;

		return $engine($template, $photo);
	}
}