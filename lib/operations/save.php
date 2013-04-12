<?php

namespace Icybee\Modules\Images\Albums;

class SaveOperation extends \Icybee\Modules\Nodes\SaveOperation
{
	protected function process()
	{
		$rc = parent::process();

		$photos = $this->request['photos'];
		$photos_model = $this->module->model('photos');

		if ($photos)
		{
			$w = 0;
			$nid = $rc['key'];

			foreach ($photos as $id)
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