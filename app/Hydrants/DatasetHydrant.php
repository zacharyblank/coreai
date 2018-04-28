<?php

namespace App\Hydrants;

class DatasetHydrant extends Hydrant {

	public function creating($model, $data)
	{
		$user = \App\Models\User::CurrentUser();
		
		$model->user()->associate($user);
	}

	public function savedUser($model, $user)
	{
		$user = User::find($user['id'])->build($user);

		$user->save();
	}

}