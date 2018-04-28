<?php

namespace App\Models;

use App\Traits\Slugable;

class Dataset extends DataModel implements \App\Repositories\Dataset
{
	use Slugable;

    public $fillable = [
    	'title'
    ];

    public $rules = [
        'title'     => 'required',
        'user'      => 'required'
    ];

    public function train()
    {
    	return $this->belongdToMany(Data::class, 'datasets_data')
    		->wherePivot('type', 'train');
    }

    public function val()
    {
    	return $this->belongdToMany(Data::class, 'datasets_data')
    		->wherePivot('type', 'val');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

}
