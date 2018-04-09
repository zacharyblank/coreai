<?php

namespace App\Http\Middleware;

use Closure;
use \App\Repositories\User;
use Tymon\JWTAuth\JWTAuth;
use Route;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $user;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(JWTAuth $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Some endpoints have optional user proporties. If an access_token is passed
        // Check it's validity and process the additional properties
        
        $method = $request->getMethod();

        if ($method == 'OPTIONS') {
            return $next($request);
        }

        $route = Route::getRoutes()->match($request);
        $action = $route->getActionName();
        
        if ($action == 'Closure') {
            return $next($request);
        }

        $controller = '\\' . substr($action, 0, strpos($action, '@'));
        $method = substr($action, strpos($action, '@')+1, strlen($action));

        try {
            $user = $this->auth->parseToken()->authenticate();

            $this->user->setCurrentUser($user);
        } catch (\Exception $e) {
            if (isset($controller::$authentication) && in_array($method, $controller::$authentication)) {
                if ( ! $this->auth->getToken()) {
                    return response()->error("Authentication Required", 401);
                }

                return response()->error($e->getMessage(), 401);
            }
        }

        return $next($request);
    }
}
