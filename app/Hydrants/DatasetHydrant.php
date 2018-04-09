<?php

namespace App\Hydrants;

use App\Repositories\User;

class DatasetHydrant {

	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function build($model, $data)
	{
		if ($model->exists) {

			if (isset($data['user'])) {
				$model->user()->associate($this->user->find($data['user']['id']));
			}

		} else {

			$model->user()->associate($this->user->create([
				"name"	=> "foobar",
				"email"	=> "test12@email.com",
				"password" => "password"
			]));

		}
	}
}