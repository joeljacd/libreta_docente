<?php

namespace App\Http\Middleware;

use App\AuthToken\JWToken;
use App\Helpers\EstadoTransaccion;
use Closure;

class ValidarToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $obj_JWToken = new JWToken;
        $et = new EstadoTransaccion();

        $token = $request->header('Authorization');
        
        if (! $token) {
            $et->status  = EstadoTransaccion::$codeUnauthorized;
            $et->error   = true;
            $et->mensaje = EstadoTransaccion::$noExistenToken;
        } else {
            $datos = $obj_JWToken->validarToken($token);
            if ($datos['valido']) {
               return $next($request);
        	   // return $next($request)->header('TOKEN', $datos['nuevoToken']);
            } else {
                $et->status = EstadoTransaccion::$codeUnauthorized;
                $et->error = true;
                $et->mensaje = EstadoTransaccion::$tokenInvalido;
            }
        }
        return response()->json($et,$et->status);
    }
}