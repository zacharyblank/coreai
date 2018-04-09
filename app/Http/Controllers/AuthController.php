<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User;
use App\Http\Resources\AuthResource;

class AuthController extends Controller
{

	protected $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
    	$this->user = $user;
    }

    /**
     * Sign in
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $this->user->authenticate($request->input('email'), $request->input('password'));
        } catch (\Exception $e) {
            throw new \App\Exceptions\JsonException($e->getMessage(), 400, 'email', 'invalid');
        }

        $token = auth()->fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * Sign up
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $user = $this->user->create($request->all());

        $this->user->setCurrentUser($user);

        $token = auth()->attempt($request->all());

        return $this->respondWithToken($token);
    }    

	/**
     * Refresh the token
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $token = auth()->parseToken()->refresh();

        $this->user->setCurrentUser(auth()->toUser());

        return $this->respondWithToken($token);     
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
