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

class DeletePhotoOperation extends \ICanboogie\Operation
{
	protected function get_controls()
	{
		return array
		(
			self::CONTROL_RECORD
		)

		+ parent::get_controls();
	}

	protected function lazy_get_model()
	{
		return ActiveRecord\get_model('images.albums/photos');
	}

	protected function validate(\ICanBoogie\Errors $errors)
	{
		return true;
	}

	protected function process()
	{
		$id = $this->request['id'];

		$this->model->delete($id);

		$this->response->message = $this->format("The photo %id has been deleted.", [ '%id' => $id ]);

		return true;
	}
}
