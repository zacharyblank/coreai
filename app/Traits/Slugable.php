<?php

namespace App\Traits;

trait Slugable {

	public static function bootSlug()
	{
		static::saving(function($model) {
			if ( ! in_array('slug', $model->fillable)) {
				$model->fillable[] = 'slug';
			}

			$model->slug = str_slug($model->title);
		});
	}	
}
