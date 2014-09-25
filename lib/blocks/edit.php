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

use Brickrouge\Element;
use Brickrouge\ElementIsEmpty;
use Brickrouge\Form;
use Brickrouge\Group;
use Brickrouge\Text;

use Icybee\Modules\Editor\RTEEditorElement;
use Icybee\Modules\Images\PopImage;
use Icybee\Modules\Images\PopOrUploadImage;

class EditBlock extends \Icybee\Modules\Nodes\EditBlock
{
	static protected function add_assets(\Brickrouge\Document $document)
	{
		parent::add_assets($document);

		$document->css->add('edit.css');
		$document->js->add('edit.js');
	}

	protected function lazy_get_attributes()
	{
		return \ICanBoogie\array_merge_recursive
		(
			parent::lazy_get_attributes(), array
			(
				Element::GROUPS => array
				(
					'album' => array
					(
						'title' => 'Photos',
						'description' => "Les photos peuvent être ordonnées par glisser-déposer."
					)
				)
			)
		);
	}

	protected function lazy_get_children()
	{
		return array_merge
		(
			parent::lazy_get_children(), array
			(
				'slides' => new AlbumEditor
				(
					$this->record, array
					(
						Element::GROUP => 'album'
					)
				),

				'image_id' => new PopOrUploadImage
				(
					array
					(
						Group::LABEL => 'Poster',
						Element::DESCRIPTION => "Poster de l'album. La première photo de l'album est utilisée par défaut."
					)
				)
			)
		);
	}
}

class AlbumEditor extends Element
{
	/**
	 * Album to edit.
	 *
	 * @var Album
	 */
	protected $album;

	public function __construct(Album $album=null, array $attributes=array())
	{
		$this->album = $album;

		parent::__construct
		(
			'div', $attributes + array
			(
				Element::IS => 'AlbumEditor',

				'class' => 'widget-album-editor'
			)
		);
	}

	protected function render_inner_html()
	{
		global $core;

		if (!$this->album)
		{
			throw new ElementIsEmpty;
		}

		$html = '';
		$photos = array_merge($this->album->photos, $core->models['images.albums/photos']->filter_by_nid(0)->all);

		foreach ($photos as $photo)
		{
			$html .= new PopPhoto(array('value' => $photo));
		}

		$add = new AddPhoto();

		return <<<EOT
<div class="album-content">
$add $html
</div>
EOT;
	}
}

class AdjustPhoto extends Group
{
	protected $elements = array();

	public function __construct(array $attributes=array())
	{
		global $core;

		parent::__construct
		(
			$attributes + array
			(
				Element::CHILDREN => array
				(
					'image_id' => $this->elements['image_id'] = new PopOrUploadImage
					(
						array
						(
							Element::REQUIRED => true
						)
					),

					new Group
					(
						array
						(
							Element::CHILDREN => array
							(
								'title' => $this->elements['title'] = new Text
								(
									array
									(
										Text::ADDON => '<i class="icon-pencil"></i>',
										Text::ADDON_POSITION => 'before'
									)
								),

								'link' => $this->elements['link'] = new Text
								(
									array
									(
										Text::ADDON => '<i class="icon-link"></i>',
										Text::ADDON_POSITION => 'before'
									)
								),

								'is_target_blank' => $this->elements['is_target_blank'] = new Element(Element::TYPE_CHECKBOX, [

									Element::LABEL => "Ouvrir le lien dans une nouvelle fenêtre"

								])
							),

							'name' => 'metas'
						)
					),

					'caption' => $this->elements['caption'] = $core->editors['rte']->from
					(
						array
						(
							RTEEditorElement::ACTIONS => 'minimal',

							'rows' => 5,
							'cols' => 32
						)
					)
				),

				Element::IS => 'AdjustPhoto',

				'class' => 'widget-adjust-photo'
			)
		);
	}

	public function offsetSet($attribute, $value)
	{
		global $core;

		if ($attribute == 'value' && $value && !($value instanceof Photo))
		{
			$value = $core->models['images.albums/photos'][$value];

			if ($value)
			{
				foreach ($value->to_array() as $name => $v)
				{
					if (empty($this->elements[$name]))
					{
						continue;
					}

					$el = $this->elements[$name];

					if ($el->type == Element::TYPE_CHECKBOX)
					{
						$el['checked'] = !!$v;
					}
					else
					{
						$el['value'] = $v;
					}
				}
			}
		}

		parent::offsetSet($attribute, $value);
	}
}

class PopPhoto extends Element
{
	static protected function add_assets(\Brickrouge\Document $document)
	{
		parent::add_assets($document);

		$document->js->add('edit.js');
	}

	public function __construct(array $attributes=array())
	{
		parent::__construct
		(
			'div', $attributes + array
			(
				Element::IS => 'PopPhoto',

				'data-adjust' => 'adjust-photo',

				'class' => 'spinner widget-pop-photo',
				'tabindex' => 0,
				'name' => 'photos[]'
			)
		);
	}

	public function offsetSet($attribute, $value)
	{
		global $core;

		if ($attribute == 'value' && $value && !($value instanceof Photo))
		{
			$value = $core->models['images.albums/photos'][$value];
		}

		parent::offsetSet($attribute, $value);
	}

	protected function alter_dataset(array $dataset)
	{
		return parent::alter_dataset($dataset) + array
		(
			'photo' => $this['value'] ? $this['value']->id : null
		);
	}

	protected function render_inner_html()
	{
		global $core;

		$photo = $this['value'];
		$thumbnail = $photo->image->thumbnail('w:128;h:128');
		$name = \Brickrouge\escape($this['name']);
		$value = $photo->id;

		return <<<EOT
{$thumbnail}
<input type="hidden" name="{$name}" value="{$value}" />
<button type="button" data-dismiss="value" class="close">×</button>
EOT;
	}
}

class AddPhoto extends PopPhoto
{
	public function __construct(array $attributes=array())
	{
		parent::__construct
		(
			$attributes + array
			(
				Element::IS => 'AddPhoto',

				'class' => 'widget-add-photo spinner',
				'dropzone' => 'file:image'
			)
		);
	}

	protected function render_inner_html()
	{
		return '<i class="icon-plus"></i>';
	}
}

namespace Brickrouge\Widget;

class AdjustPhoto extends \Icybee\Modules\Images\Albums\AdjustPhoto
{

}