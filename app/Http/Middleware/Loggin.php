<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Loggin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle(Request $request, Closure $next)
    {
        // try {
        //     if(env('APP_DEV') && Auth::user()->role_id != 1){
        //         return redirect('/admin/notice');
        //     }
        // } catch (\Throwable $th) {}

        // Evitar que registre cuando el usuario ingrese a la lista de logs
        if (!str_contains(request()->url(), 'admin/compass')) {
            try {
                $data = [
                    'user_id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'ip' => request()->ip(),
                    // 'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                    'method' => request()->method(),
                    'input' => request()->except(['password', '_token', '_method']),
                ];
                Log::channel('requests')->info('Petición HTTP al sistema.', $data);
            } catch (\Throwable $th) {
                $data = [
                    'ip' => request()->ip(),
                    // 'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                    'method' => request()->method(),
                    'input' => request()->except(['password', '_token', '_method']),
                ];
                Log::channel('requests')->info('Petición HTTP al sistema.', $data);
            }
        }

        return $next($request);
    }
}
