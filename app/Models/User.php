<?php

namespace App\Models;

// use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Hydratable;

class User extends Authenticatable implements \App\Repositories\User, JWTSubject
{
	use Hydratable;
	
	protected $fillable = [
        'first_name',
		'last_name',
		'email',
		'password',
	];

    protected static $currentUser;

    /**
     * The authentication identifier.
     *
     * @var string
     */
    protected $authIdentifier = 'email';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return array();
    }

	/**
     * Set the current user.
     *
     * @return void
     */
    public static function setCurrentUser($user)
    {        
        static::$currentUser = $user;
    }

    /**
     * Get the current user.
     *
     * @return mixed
     */
    public static function currentUser()
    {
        return (static::$currentUser) ? static::$currentUser : null;
    }

    /**
     * Authenticate the user
     * @param $value
     */
    public function authenticate($identifier, $password)
    {
        if (\Auth::once([
            'email' => $identifier,
            'password' => $password
        ])) {
            $user = \Auth::user();
            
            static::setCurrentUser($user);

            return $user;
        }
        
        throw new \Exception('Email and password did not match');
    }

    /**
     * Set the password attribute before save or update
     * @param $value
     */
    protected function setPasswordAttribute($value)
    {
        if ( ! empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }    
}