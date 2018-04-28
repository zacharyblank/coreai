<?php

namespace App\Hydrants;

class Hydrant {

	protected $data = [];

	public function getData(string $key = null)
	{
		if ($key) {
			if (isset($this->data[$key])) {
				return $this->data[$key];
			} else {
				return null;
			}
		}

		return $this->data;
	}

	public function setData(array $data)
	{
		$this->data = $data;
	}	

	public function creating($model, $data) { return true; }
	public function created($model, $data) { return true; }
	public function saving($model, $data) { return true; }
	public function saved($model, $data) { return true; }
	public function updating($model, $data) { return true; }
	public function updated($model, $data) { return true; }

}