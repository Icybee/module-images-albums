<?php

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

		$this->response->message = array("The photo %id has been deleted.", array('%id' => $id));

		return true;
	}
}