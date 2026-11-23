<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get(config('admin.session_key'))) {
            return redirect()
                ->guest(route('admin.login'))
                ->withErrors([
                    'username' => 'Faça login para acessar a área administrativa.',
                ]);
        }

        return $next($request);
    }
}
