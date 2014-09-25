<?php

namespace Icybee\Modules\Images\Albums;

use ICanBoogie\HTTP\Request;

/**
 * Save an album and its photos.
 *
 * Photos that are no longer attached are delete.
 */
class SaveOperation extends \Icybee\Modules\Nodes\SaveOperation
{
	protected function process()
	{
		global $core;

		$rc = parent::process();
		$nid = $rc['key'];
		$photos_ids = $this->request['photos'];
		$photos_ids = array_combine($photos_ids, $photos_ids);
		$photos_model = $this->module->model('photos');
		$current_photos_ids = $photos_model->select('id, id')->filter_by_nid($nid)->pairs;

		#
		# Delete photos which are no longer used
		#

		$errors = $this->response->errors;

		foreach (array_diff($current_photos_ids, $photos_ids) as $id)
		{
			/* @var $response \ICanBoogie\HTTP\Response */

			$response = Request::from([

				'path' => $core->routes['api:images.albums:photo:delete']->format([ 'id' => $id ]),
				'is_delete' => true

			])->send();

			if (!$response->is_successful)
			{
				$errors['photos'][] = $errors->format("An error occured while deleting photo %id", [ 'id' => $id ]);
			}
		}

		#
		# Attach the specified photos to the album, and set their weight.
		#

		if ($photos_ids)
		{
			$w = 0;

			foreach ($photos_ids as $id)
			{
				$photo = $photos_model[$id];
				$photo->nid = $nid;
				$photo->weight = $w++;
				$photo->save();
			}
		}

		return $rc;
	}
}