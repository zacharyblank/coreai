<?php

namespace App\Traits;

trait Hydratable {

	protected static $hydrant;
	
	public static function hydrant($hydrator)
	{
		static::$hydrant = app()->make($hydrator);
	}

	public function build($data)
	{
		$this->fill($data);

		static::$hydrant->build($this, $data);

		return $this;
	}
}