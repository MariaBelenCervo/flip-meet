<?php
namespace Middlewares;

use Core\Middleware;
use \Closure;
use Auth\Auth;
use Exceptions\UnauthorizedHttpException;

class AuthMiddleware implements Middleware {

    public function handle($request, Closure $next)
    {
		// Capturo el token
        $token = $_SERVER['HTTP_X_TOKEN'] ?? null;

        // Verifico que no sea nulo y que sea válido.
		if($token === null || !Auth::validateToken($token)) {
            throw new UnauthorizedHttpException("Debe iniciar sesión para realizar esta acción.", "No autorizado");
        }

        return $next(Auth::validateToken($token));
    }
}



