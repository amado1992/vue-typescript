<?php

namespace App\Http\Middleware;

use Closure;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->headers->get('Authorization')) {
            $ha = explode(' ', $request->headers->get('Authorization'));
            if (count($ha) !== 2) {
                return response()->json(['code' => 'format', 'message' => 'El formato del token no es válido.']);
            }
            if (!session()->exists('user.token')) {
                return response()->json(['code' => 'session', 'message' => 'session timeout.']);
            }
            if ($ha[1] !== session()->get('user.token')) {
                return response()->json(['code' => 'token', 'message' => 'El token no es válido.']);
            }
            return $next($request);
        }
        return response()->json(['code' => 'La petición no tiene el token de verificación.']);
    }
}
