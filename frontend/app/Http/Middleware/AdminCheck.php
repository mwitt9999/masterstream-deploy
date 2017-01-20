<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
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
        $user = Auth::user();

        if($user->type !== 'admin'){
            session()->flash('app_error', 'You do not have sufficient privileges to access that area');
            return redirect()->back();
        }

        return $next($request);
    }
}
