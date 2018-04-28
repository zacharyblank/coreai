<?php

namespace App\Traits;

trait Slugable {

	public static function bootSlugable()
	{
		static::building(function($model) {
			if ( ! in_array('slug', $model->fillable)) {
				$model->fillable[] = 'slug';
			}
		});

		static::saving(function($model) {
			$model->slug = str_slug($model->title);
		});
	}	
}
