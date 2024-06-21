<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Middleware RedirectIfNotAuthenticated: Checking authentication');
    
        if (!Auth::check()) {
            Log::info('User not authenticated, redirecting to login');
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        Log::info('User authenticated, proceeding to next request');
        return $next($request);
    }
}
