<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class hasToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::exists('access_token')) {
            $authService = new AuthService();
            $tokenStatus = $authService->checkToken()->getData('data')['status'];

            if ($tokenStatus === 'success') {
                return $next($request);
            }
        }

        Session::flush();
        return redirect()->route('auth.index');
    }
}
