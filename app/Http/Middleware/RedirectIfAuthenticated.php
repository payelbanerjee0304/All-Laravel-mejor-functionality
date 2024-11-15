<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, string ...$guards): Response
    // {
    //     $guards = empty($guards) ? [null] : $guards;

    //     foreach ($guards as $guard) {
    //         if (Auth::guard($guard)->check()) {
    //             // Capture the current URL
    //             $currentUrl = url()->current();

    //             // Redirect back to the current page
    //             return redirect($currentUrl);
    //         }
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('admin_id')) 
        {
            return redirect()->route('admin.listing');
        }
        return $next($request);
    }
}