<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPerfomanceAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has is_perfomance = 1
        if (Auth::check() && Auth::user()->is_perfomance == 1) {
            return $next($request);
        }

        // Redirect back with error message if not allowed
        return redirect()->route('user.dashboard')->with('error', 'You do not have access to this page.');
    }
}

 ?>
