<?php

namespace App\Traits;

trait Hydratable {

	protected static $hydrant;

	public static function bootHydratable()	
	{
		static::saving(function($model) {
			if (static::$hydrant) {        		
		        foreach (static::$hydrant->getData() as $key => $data) {
		        	$method = "saving" . ucfirst($key);
		        	if (method_exists(static::$hydrant, $method)) {
			        	static::$hydrant->$method($model, $data);
		        	}
		        }

				static::$hydrant->saving($model, $data);
            }
		});

		static::creating(function($model) {
			if (static::$hydrant) {
        		$model->validate();

		        foreach (static::$hydrant->getData() as $key => $data) {
		        	$method = "creating" . ucfirst($key);
		        	if (method_exists(static::$hydrant, $method)) {
			        	static::$hydrant->$method($model, $data);		        		
		        	}
		        }

				static::$hydrant->creating($model, $data);
			}
		});

		static::created(function($model) {
			if (static::$hydrant) {
		        foreach (static::$hydrant->getData() as $key => $data) {
		        	$method = "created" . ucfirst($key);
		        	if (method_exists(static::$hydrant, $method)) {
			        	static::$hydrant->$method($model, $data);		        		
		        	}
		        }

				static::$hydrant->created($model, $data);
			}
		});
		

		static::saved(function($model) {

			if (static::$hydrant) {
		        foreach (static::$hydrant->getData() as $key => $data) {
		        	$method = "saved" . ucfirst($key);
		        	if (method_exists(static::$hydrant, $method)) {
			        	static::$hydrant->$method($model, $data);		        		
		        	}
		        }

				static::$hydrant->saved($model, $data);
			}
		});
	}
	
    public function validate()
    {
		if ( ! static::$hydrant) {
			return;
		}

    	$rules = ($this->rules ? $this->rules : []);

    	$data = array_merge($this->toArray(), static::$hydrant->getData());

        $validator = \Validator::make($data, $rules);

		if ($validator->fails()) {
            throw new \App\Exceptions\ValidationException($validator->errors()->first(), 400, array_keys($validator->errors()->getMessages())[0], 'invalid');
        }
    }

	public static function hydrant($hydrator)
	{
		static::$hydrant = new $hydrator();
	}

    public static function building($callback)
    {
        static::registerModelEvent('building', $callback);
    }

    public static function built($callback)
    {
        static::registerModelEvent('built', $callback);
    }

	public function build($data)
	{
        $this->fireModelEvent('building');

		$this->fill($data);

		if ( static::$hydrant) {
			static::$hydrant->setData($data);
		}
        
        $this->fireModelEvent('built');

		return $this;
	}
}