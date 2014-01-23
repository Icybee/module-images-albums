<?php

namespace Icybee\Modules\Images\Albums;

class UpdatePhotoOperation extends \ICanBoogie\Operation
{
	protected function get_controls()
	{
		return array
		(
			self::CONTROL_PERMISSION => Module::PERMISSION_MAINTAIN
		)

		+ parent::get_controls();
	}

	protected function get_model()
	{
		global $core;

		return $core->models['images.albums/photos'];
	}

	protected function validate(\ICanBoogie\Errors $errors)
	{
		$id = $this->request['id'];

		if ($id)
		{
			if (!$this->model->exists($id))
			{
				$errors['id'] = "The record does not exists.";
			}
		}

		return $errors->count() == 0;
	}

	protected function process()
	{
		$id = $this->model->save
		(
			$this->request->params, $this->request['id']
		);

		$record = $this->model[$id];

		return new PopPhoto(array('value' => $record));
	}
}
